<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\PKActivity;
use App\Models\PKActivityBarangay;
use App\Models\PKActivityHRH;
use App\Models\PKActivityProgram;
use App\Models\Program;
use App\Models\ProgrammaticIndicator;
use App\Models\Report;
use App\Models\ReportUser;
use App\Models\ReportValue;
use App\Models\ReportValueDisaggregation;
use App\Models\User;
use App\Services\BarangayService;
use App\Services\ProgramService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PKActivityController extends Controller
{

    public function __construct(
        protected BarangayService $barangayService,
        protected ProgramService $programService,
        protected UserService $userService,

    ) {}

    public function index(Request $request){
        $searchKeyword = $request->search;
        $user = $request->user();

        $activities = PKActivity::query()
            ->when($searchKeyword, function ($query, $keyword) {
                $query->where('activity_name', 'like', '%' . $keyword . '%');
            })
            ->when($user->access_level === 2, function ($query) use($user) {
                $query->where('submitted_by', $user->id)
                        ->orWhereHas('hrh', function($query) use($user) {
                            $query->where('user_id', $user->id);
                        });
            })
            ->when($user->access_level === 3, function ($query) use($user) {
                $query->whereHas('barangays', function($query) use($user) {
                    $query->where('province_id', $user->province_id);
                });
                        
            })
            ->when($user->access_level === 4, function ($query) use($user) {
                $query->whereHas('barangays', function($query) use($user) {
                    $query->whereIn('municipality_id', $user->handledMunicipalities->pluck('id')->toArray());
                });
                        
            })
            ->withCount(['hrh', 'barangays', 'programs', 'reports'])
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return inertia('pk_activity/index', [
            'list' => $activities,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(Request $request){
        $user = $request->user();

        $barangays = $this->barangayService->getBarangaySelection($user->access_level, $user->province_id);
        $programs = $this->programService->getProgramSelection();
        $hrh = $this->userService->getHrhSelection($user->access_level, $user->province_id);

        return inertia('pk_activity/create',[
            'barangays' => $barangays,
            'programs'=> $programs,
            'hrh'=> $hrh,
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'activity_name'=> 'required|string',
            'date_start'=> 'required|date',
            'date_end'=> 'required|date',
            'type'=> 'required|string',
            'total_clients'=> 'required|numeric',
            'barangays'=> 'required|array',
            'hrh'=> 'required|array',
            'programs'=> 'required|array',
        ]);

        $pkActivityCreated = PKActivity::create([
            'activity_name'=> $validated['activity_name'],
            'date_start'=> $validated['date_start'],
            'date_end'=> $validated['date_end'],
            'type'=> $validated['type'],
            'total_clients'=> $validated['total_clients'],
        ]);

        foreach($validated['barangays'] as $brgy){
            PKActivityBarangay::create([
                'barangay_id'=> $brgy,
                'pk_activity_id'=> $pkActivityCreated->id,
            ]);
        }

        foreach($validated['hrh'] as $hrh){
            PKActivityHRH::create([
                'user_id'=> $hrh,
                'pk_activity_id'=> $pkActivityCreated->id,
            ]);
        }

        foreach($validated['programs'] as $program){
            PKActivityProgram::create([
                'program_id'=> $program,
                'pk_activity_id'=> $pkActivityCreated->id,
            ]);
        }

        return back();
    }

    public function edit(string $id){
        $pk_activity = PKActivity::findOrFail($id);

        return inertia('pk_activity/edit',[
            'pk_activity'=> $pk_activity
        ]);
    }

    public function update(Request $request, string $id){
        $validated = $request->validate([
            'activity_name'=> 'required|string',
            'date_start'=> 'required|date',
            'date_end'=> 'required|date',
            'type'=> 'required|string',
            'total_clients'=> 'required|numeric',
        ]);

        PKActivity::find($id)->update($validated);

        return back();
    }

    public function managePrograms(string $id){
        $pk_activity = PKActivity::findOrFail($id);
        $pk_activity->load(['programs']);

        $includedProgramIDs = $pk_activity->programs->pluck('id')->toArray();
        $programs = Program::whereNotIn('id', $includedProgramIDs)->get();

        return inertia('pk_activity/manage_programs',[
            'pk_activity'=> $pk_activity,
            'programs'=> $programs
        ]);
    }

    public function manageBarangays(string $id, Request $request){
        $user = $request->user();

        $pk_activity = PKActivity::findOrFail($id);
        $pk_activity->load(['barangays.municipality.province']);

        $includedBarangayIDs = $pk_activity->barangays->pluck('id')->toArray();
        $barangays = Barangay::query()
                        ->with(['municipality.province'])
                        ->whereNotIn('id', $includedBarangayIDs)
                        ->when($user->province_id,function ($query) use ($user){
                            $query->where('province_id', $user->province_id);
                        })
                        ->get();

        return inertia('pk_activity/manage_barangays',[
            'pk_activity'=> $pk_activity,
            'barangays'=> $barangays
        ]);
    }

    public function manageHRH(string $id, Request $request){
        $user = $request->user();

        $pk_activity = PKActivity::findOrFail($id);
        $pk_activity->load(['hrh']);

        $includedHRHIDs = $pk_activity->hrh->pluck('id')->toArray();
        $hrh = User::query()
                        ->whereNotIn('id', $includedHRHIDs)
                        ->where('access_level',2)
                        ->when($user->province_id,function ($query) use ($user){
                            $query->where('province_id', $user->province_id);
                        })
                        ->get();

        return inertia('pk_activity/manage_hrh',[
            'pk_activity'=> $pk_activity,
            'hrh'=> $hrh
        ]);
    }

    public function addProgram(string $activityID, string $programID){
        PKActivityProgram::create([
            'pk_activity_id'=> $activityID,
            'program_id'=> $programID
        ]);

        return back();
    }

    public function removeProgram(string $activityProgramID){
        PKActivityProgram::find($activityProgramID)->delete();

        return back();
    }

    public function addBarangay(string $activityID, string $barangayID){
        PKActivityBarangay::create([
            'pk_activity_id'=> $activityID,
            'barangay_id'=> $barangayID
        ]);

        return back();
    }

    public function removeBarangay(string $activityBarangayID){
        PKActivityBarangay::find($activityBarangayID)->delete();

        return back();
    }

    public function addHRH(string $activityID, string $userID){
        PKActivityHRH::create([
            'pk_activity_id'=> $activityID,
            'user_id'=> $userID
        ]);

        return back();
    }

    public function removeHRH(string $activityHRHID){
        PKActivityHRH::find($activityHRHID)->delete();

        return back();
    }

    public function createReport(string $id, Request $request){
        $user = $request->user();

        $pk_activity = PKActivity::findOrFail($id);

        $barangays = Barangay::query()
                        ->with(['municipality.province'])
                        ->when($user->province_id, function($query) use ($user){
                                $query->where('province_id', $user->province_id);
                        })
                        ->get()
                        ->map(function($brgy){
                                return [
                                    'id'=> $brgy->id,
                                    'name' => $brgy->name . ', ' . $brgy->municipality?->name . ', ' . $brgy->municipality?->province?->name,
                                ];
                        });
        $users = User::query()
                    ->where('access_level', 2)
                    ->when($user->province_id, function($query) use ($user){
                        $query->where('province_id', $user->province_id);
                    })
                    ->get();
        $indicators = ProgrammaticIndicator::with(['disaggregations','program'])->where('active', 1)->get();

        return inertia('pk_activity/create_report',[
            'pk_activity'=> $pk_activity,
            'barangays'=> $barangays,
            'users'=> $users,
            'indicators' => $indicators
        ]);
    }

    public function storeReport(Request $request){
        $validated = $request->validate([
            'report' => 'required|array',
            'report.date' => 'required|date',
            'report.total_clients' => 'required|numeric',
            'report.total_returning_clients' => 'required|numeric',
            'report.barangay_id' => 'required|numeric|exists:barangays,id',
            'report.pk_activity_id' => 'required|numeric|exists:pk_activities,id',

            'indicators' => 'required|array',
            'indicators.*.id' => 'required|numeric|exists:programmatic_indicators,id',
            'indicators.*.program_id'=> 'required|numeric|exists:programs,id',
            'indicators.*.total'=> 'required|numeric',

            'indicators.*.disaggregations' => 'required|array',
            'indicators.*.disaggregations.*.id'=> 'required|numeric|exists:disaggregations,id',
            'indicators.*.disaggregations.*.value'=> 'required|numeric',

            'users'=> 'required|array',
            'users.*'=> 'required|exists:users,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $report = Report::create($validated['report']);

                foreach ($validated['indicators'] as $ind) {
                    $report_value = ReportValue::create([
                        'report_id' => $report->id,
                        'indicator_id' => $ind['id'],
                        'program_id' => $ind['program_id'],
                        'total' => $ind['total'],
                    ]);

                    foreach ($ind['disaggregations'] as $dis) {
                        ReportValueDisaggregation::create([
                            'report_value_id' => $report_value->id,
                            'disaggregation_id' => $dis['id'],
                            'value' => $dis['value'],
                        ]);
                    }
                }

                foreach ($validated['users'] as $user) {
                    ReportUser::create([
                        'report_id' => $report->id,
                        'user_id' => $user,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            report($e);
            return back();
        }

        return back();
    }

    public function viewPKActivity(string $id){
        $pk_activity = PKActivity::findOrFail($id);
        $pk_activity->load([
            'reports.values.disaggregations.disaggregation', 
            'reports.values.indicator', 
            'reports.barangay.municipality.province',
            'hrh', 
            'barangays.municipality.province',
            'programs',
            'reports.actionBy',
            'reports.users'
        ]);

        return inertia('pk_activity/view_pk_activity',[
            'pk_activity'=> $pk_activity
        ]);
    }

    public function editReport(string $id, Request $request)
    {
        $user = $request->user();
        $report = Report::with(['users', 'values.disaggregations.disaggregation'])->findOrFail($id);
        $indicators = ProgrammaticIndicator::with(['disaggregations', 'program'])->where('active', 1)->get();

        $existingIndicatorIds = $report->values->pluck('indicator_id')->toArray();
        $missingIndicators = $indicators->whereNotIn('id', $existingIndicatorIds);

        DB::transaction(function () use ($report, $missingIndicators) {
            foreach ($missingIndicators as $indicator) {
                $reportValue = $report->values()->create([
                    'indicator_id' => $indicator->id,
                    'program_id'   => $indicator->program?->id,
                    'total'        => 0,
                ]);

                if ($indicator->disaggregations->isNotEmpty()) {
                    $disaggs = $indicator->disaggregations->map(fn ($d) => [
                        'report_value_id'   => $reportValue->id,
                        'disaggregation_id' => $d->id,
                        'value'             => 0,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ])->toArray();

                    ReportValueDisaggregation::insert($disaggs);
                }
            }
        });

        $report->load(['values.disaggregations.disaggregation','values.indicator.disaggregations']);
        $barangays = Barangay::with('municipality.province')
            ->when($user->province_id, fn ($q, $pId) => $q->whereHas('municipality', fn ($m) => $m->where('province_id', $pId)))
            ->get()
            ->map(fn ($b) => [
                'id'   => $b->id,
                'name' => implode(', ', array_filter([$b->name, $b->municipality?->name, $b->municipality?->province?->name])),
            ]);

        $users = User::select(['id', 'name', 'province_id'])
            ->where('access_level', 2)
            ->when($user->province_id, fn ($q, $pId) => $q->where('province_id', $pId))
            ->get();

        return inertia('pk_activity/edit_report', [
            'report' => $report, 
            'barangays' => $barangays, 
            'users' => $users
        ]);
    }

    public function updateReport(Request $request){
        $validated = $request->validate([
            'report' => 'required|array',
            'report.id' => 'required|numeric|exists:reports,id',
            'report.date' => 'required|date',
            'report.total_clients' => 'required|numeric',
            'report.total_returning_clients' => 'required|numeric',
            'report.barangay_id' => 'required|numeric|exists:barangays,id',

            'indicators' => 'required|array',
            'indicators.*.id' => 'required|numeric|exists:report_values,id',
            'indicators.*.total'=> 'required|numeric',

            'indicators.*.disaggregations' => 'required|array',
            'indicators.*.disaggregations.*.id'=> 'required|numeric|exists:report_value_disaggregations,id',
            'indicators.*.disaggregations.*.value'=> 'required|numeric',

            'users'=> 'required|array',
            'users.*'=> 'required|exists:users,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $report = Report::findOrFail($validated['report']['id']);
                $report->update([
                    'date' => $validated['report']['date'],
                    'total_clients' => $validated['report']['total_clients'],
                    'total_returning_clients' => $validated['report']['total_returning_clients'],
                ]);

                foreach ($validated['indicators'] as $ind) {
                    $report_value = ReportValue::find($ind['id']);
                    $report_value->update([
                        'total' => $ind['total'],
                    ]);

                    foreach ($ind['disaggregations'] as $dis) {
                        ReportValueDisaggregation::find($dis['id'])->update([
                            'value' => $dis['value'],
                        ]);
                    }
                }

                $report->users()->detach();
                foreach ($validated['users'] as $user) {
                    ReportUser::create([
                        'report_id' => $validated['report']['id'],
                        'user_id' => $user,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            report($e);
            return response()->json($e->getMessage());
        }

        return back();
    }

    public function resubmitReport(string $id, Request $request){
        $report = Report::findOrFail($id);
        $report->update([
            'status' => 'Pending',
            'action_by' => $request->user()->id
        ]);

        return back();
    }
}

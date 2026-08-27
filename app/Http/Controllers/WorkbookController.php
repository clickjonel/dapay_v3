<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\Report;
use App\Models\ReportValueDisaggregation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkbookController extends Controller
{
    public function fourPSWorkbook(){
        $results = DB::table('report_value_disaggregations as rvd')
            ->join('report_values as rv', 'rvd.report_value_id', '=', 'rv.id')
            ->join('programmatic_indicators as pi', 'rv.indicator_id', '=', 'pi.id')
            ->join('reports as r', 'rv.report_id', '=', 'r.id')
            ->join('barangays as b', 'r.barangay_id', '=', 'b.id')
            ->join('provinces as p', 'b.province_id', '=', 'p.id')
            ->where('rvd.disaggregation_id', 3)
            ->select(
                'p.id as province_id',
                'p.name as province_name',
                'pi.id as indicator_id',
                'pi.name as indicator_name',
                DB::raw('SUM(rvd.value) as total_4ps_value')
            )
            ->groupBy('p.id', 'p.name', 'pi.id', 'pi.name')
            ->orderBy('p.name')
            ->orderBy('pi.id')
            ->get()
            ->groupBy('province_id')
            ->map(function ($items) {
                $first = $items->first();
                return [
                    'province_id'   => $first->province_id,
                    'province_name' => $first->province_name,
                    'indicators'    => $items->map(function ($item) {
                        return [
                            'indicator_id'    => $item->indicator_id,
                            'indicator_name'  => $item->indicator_name,
                            'total_4ps_value' => (float) $item->total_4ps_value,
                        ];
                    })->values()->all(),
                ];
            })->values();

        return inertia('workbook/4Ps', [
            'provinces' => $results,
        ]);
    }

    public function totalClientsServed(){
        $totalServed = Province::query()
            ->select('provinces.id')
            ->selectRaw('SUM(report_values.total) as total_served')
            ->join('barangays', 'barangays.province_id', '=', 'provinces.id')
            ->join('reports', 'reports.barangay_id', '=', 'barangays.id')
            ->join('report_values', 'report_values.report_id', '=', 'reports.id')
            ->where('reports.status', 'Approved')
            ->groupBy('provinces.id')
            ->pluck('total_served', 'provinces.id');

        $totalTargets = Province::query()
            ->select('provinces.id')
            ->selectRaw('SUM(barangay_priority_programs.target) as total_targets')
            ->join('barangays', 'barangays.province_id', '=', 'provinces.id')
            ->join(
                'barangay_priority_programs',
                'barangays.id',
                '=',
                'barangay_priority_programs.barangay_id'
            )
            ->groupBy('provinces.id')
            ->pluck('total_targets', 'provinces.id');

        $provinces = Province::select('id', 'name')
            ->get()
            ->map(fn ($province) => [
                'name' => $province->name,
                'total_served' => (int) ($totalServed[$province->id] ?? 0),
                'total_targets' => (int) ($totalTargets[$province->id] ?? 0),
            ]);

        return inertia('workbook/totalClientsServed', [
            'provinces' => $provinces,
        ]);
    }

    public function primaryCareIndicators(){
        
    }
}

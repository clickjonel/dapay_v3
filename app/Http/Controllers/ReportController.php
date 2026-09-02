<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function approve(Request $request)
    {
        $validated = $request->validate([
            'reports' => 'required|array',
            'reports.*' => 'integer|exists:reports,id',
        ]);
    
        Report::whereIn('id', $validated['reports'])->update([
            'status' => 'Approved',
            'action_by' => $request->user()->id,
        ]);
    
        return back();
    }
    
    public function reject(Request $request)
    {
        $validated = $request->validate([
            'reports' => 'required|array',
            'reports.*' => 'integer|exists:reports,id',
            'remarks' => 'required|string',
        ]);
    
        Report::whereIn('id', $validated['reports'])->update([
            'status' => 'Rejected',
            'action_by' => $request->user()->id,
            'remarks' => $validated['remarks'],
        ]);
    
        return back();
    }

}

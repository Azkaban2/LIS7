<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ActivityLog;

class AdminController extends Controller
{
    /**
     * Display the activity log for admins.
     */
    public function activityLog()
    {
        // Fetch activity logs with pagination, including user relationships
        $logs = ActivityLog::with('user')->latest()->paginate(10);

        return view('admin.activity-log', compact('logs'));
    }

    /**
     * Clear all activity logs from the database.
     */
    public function clearLogs(Request $request)
    {
        try {
            ActivityLog::truncate();  // Deletes all records from the activity_logs table
            return redirect()->route('admin.activity-log')->with('status', 'All logs cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.activity-log')->withErrors('Failed to clear logs. Please try again.');
        }
    }
    public function exportLogsPdf()
    {
        $logs = ActivityLog::with('user')->latest()->get();
    
        // Generate the current date string for the file name
        $currentDate = now()->format('Y-m-d');
    
        // Create a PDF and use the formatted date in the file name
        $pdf = Pdf::loadView('admin.activity-log-pdf', compact('logs'));
    
        // Return the PDF download with a custom file name
        return $pdf->download("Activity_Log_{$currentDate}.pdf");
    }
    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderRequest;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        // Count total order requests and results
        $orderCount = OrderRequest::count();
        $resultCount = Result::count();

        // Compute Turnaround Time (TAT) correctly (Order Creation -> PDF Generation)
        $tatValues = Result::whereNotNull('pdf_file') // Ensures only completed results
            ->join('order_requests', 'results.order_request_id', '=', 'order_requests.id')
            ->selectRaw("TIMESTAMPDIFF(MINUTE, order_requests.created_at, results.created_at) as tat_minutes")
            ->pluck('tat_minutes');

        // Compute TAT Metrics (Convert to Hours)
        $averageTAT = $tatValues->avg() ? round($tatValues->avg() / 60, 2) : 0; // Mean in hours
        $medianTAT = $tatValues->median() ? round($tatValues->median() / 60, 2) : 0; // Median in hours

        // Percentage of Tests Completed Within 24 Hours (1440 minutes)
        $tatWithin24Hours = $tatValues->filter(fn($value) => $value <= 1440)->count();
        $totalTests = $tatValues->count();
        $tatCompliancePercentage = ($totalTests > 0) ? round(($tatWithin24Hours / $totalTests) * 100, 2) : 0;

        // Convert Average TAT to Percentage (Assuming 48 hours = 100%)
        $averageTATPercentage = ($averageTAT > 0) ? round(($averageTAT / 48) * 100, 2) : 0;

        // Program-specific counts
        $programCounts = OrderRequest::selectRaw("
            SUM(CASE WHEN programs LIKE '%Hematology%' THEN 1 ELSE 0 END) AS Hematology,
            SUM(CASE WHEN programs LIKE '%Clinical Microscopy%' THEN 1 ELSE 0 END) AS Clinical_Microscopy,
            SUM(CASE WHEN programs LIKE '%Clinical Chemistry%' THEN 1 ELSE 0 END) AS Clinical_Chemistry,
            SUM(CASE WHEN programs LIKE '%Serology%' THEN 1 ELSE 0 END) AS Serology,
            SUM(CASE WHEN programs LIKE '%Electrolytes%' THEN 1 ELSE 0 END) AS Electrolytes,
            SUM(CASE WHEN programs LIKE '%ICE - ElectroCardioGram (ECG)%' THEN 1 ELSE 0 END) AS ECG
        ")->first()->toArray();

        // Gender Distribution
        $genderCounts = OrderRequest::selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        // Age Distribution
        $ageCounts = OrderRequest::selectRaw("
            CASE
                WHEN age BETWEEN 0 AND 18 THEN '0-18'
                WHEN age BETWEEN 19 AND 35 THEN '19-35'
                WHEN age BETWEEN 36 AND 60 THEN '36-60'
                ELSE '60+'
            END as age_range, COUNT(*) as count
        ")
        ->groupBy('age_range')
        ->pluck('count', 'age_range');

        //  Check User Type
        $userType = Auth::user()->usertype;

        //  Return Dashboard View with Data
        return view($userType === 'admin' ? 'admin.dashboard' : 'dashboard', compact(
            'orderCount',
            'resultCount',
            'programCounts',
            'genderCounts',
            'ageCounts',
            'averageTAT',
            'medianTAT',
            'tatCompliancePercentage',
            'averageTATPercentage'
        ));
    }
}

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

        // Program-specific counts
        $programCounts = [
            'Hematology' => OrderRequest::where('programs', 'like', '%Hematology%')->count(),
            'Clinical Microscopy' => OrderRequest::where('programs', 'like', '%Clinical Microscopy%')->count(),
            'Clinical Chemistry' => OrderRequest::where('programs', 'like', '%Clinical Chemistry%')->count(),
            'Serology' => OrderRequest::where('programs', 'like', '%Serology%')->count(),
            'Electrolytes' => OrderRequest::where('programs', 'like', '%Electrolytes%')->count(),
        ];

        // Gender distribution
        $genderCounts = OrderRequest::selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender');

        // Age distribution
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

        // Check user type
        $userType = Auth::user()->usertype;

        if ($userType === 'admin') {
            // Admin dashboard view
            return view('admin.dashboard', compact(
                'orderCount',
                'resultCount',
                'programCounts',
                'genderCounts',
                'ageCounts'
            ));
        } else {
            // Regular user dashboard view
            return view('dashboard', compact(
                'orderCount',
                'resultCount',
                'programCounts',
                'genderCounts',
                'ageCounts'
            ));
        }
    }
}


<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\OrderRequest;
use App\Models\Result;
use App\Models\SelectedMachine;
use Illuminate\Support\Facades\File;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Log;

class ResultController extends Controller
{
    public function showInstrumentImportForm()
    {
        $lastMachine = SelectedMachine::where('user_id', auth()->id())->value('machine');
        return view('results.instrument_import', compact('lastMachine'));
    }
    

    public function getPatientsByMachine(Request $request)
    {
        $machineProgramMapping = [
            'machine_1' => 'Hematology',
            'machine_2' => 'Electrolytes',
            'machine_3' => 'Clinical Chemistry',
            'machine_4' => 'Serology',
            'machine_5' => 'Clinical Microscopy',
            'machine_6' => 'ICE - ElectroCardioGram (ECG)',
        ];

        $program = $machineProgramMapping[$request->machine] ?? null;

        if ($program) {
            $patients = OrderRequest::where('programs', 'like', '%' . $program . '%')
                ->pluck('patient_name', 'id');

            return response()->json($patients);
        }

        return response()->json([], 404);
    }

    public function loadAutomaticData(Request $request)
{
    // Get the last selected machine for the authenticated user
    $lastMachine = SelectedMachine::where('user_id', auth()->id())->value('machine');
    if (!$lastMachine) {
        return response()->json(['error' => 'No machine selected'], 400);
    }


    // Retrieve the most recent patient for the selected program
    $orderRequest = OrderRequest::where('programs', 'like', '%' . $program . '%')
        ->latest('created_at') // Assuming `created_at` is a timestamp column
        ->first();

    if ($orderRequest) {
        return response()->json([
            'patient_name' => $orderRequest->patient_name,
            'patient_id' => $orderRequest->patient_id,
            'gender' => $orderRequest->gender,
            'age' => $orderRequest->age,
            'birthday' => $orderRequest->birthday,
            'test_program' => $orderRequest->programs,
            'tests_ordered' => $orderRequest->order,
            'test_code' => $orderRequest->test_code,
            'sample_type' => $orderRequest->sample_type,
            'date_performed' => $orderRequest->date_performed,
            'date_released' => $orderRequest->date_released,
            'physician_full_name' => $orderRequest->physician_full_name,
            'medtech_full_name' => $orderRequest->medtech_full_name,
            'medtech_lic_no' => $orderRequest->medtech_lic_no,
            'pathologist_full_name' => $orderRequest->pathologist_full_name,
            'pathologist_lic_no' => $orderRequest->pathologist_lic_no,
        ]);
    }

    return response()->json(['error' => 'No patient data found'], 404);
}


    public function getPatientDetails(Request $request)
    {
        $orderRequest = OrderRequest::find($request->patient_id);

        if ($orderRequest) {
            return response()->json([
                'patient_name' => $orderRequest->patient_name,
                'patient_id' => $orderRequest->patient_id,
                'gender' => $orderRequest->gender,
                'age' => $orderRequest->age,
                'birthday' => $orderRequest->birthday,
                'test_program' => $orderRequest->programs,
                'tests_ordered' => $orderRequest->order,
                'test_code' => $orderRequest->test_code,
                'sample_type' => $orderRequest->sample_type,
                'date_performed' => $orderRequest->date_performed,
                'date_released' => $orderRequest->date_released,
                'physician_full_name' => $orderRequest->physician_full_name,
                'medtech_full_name' => $orderRequest->medtech_full_name,
                'medtech_lic_no' => $orderRequest->medtech_lic_no,
                'pathologist_full_name' => $orderRequest->pathologist_full_name,
                'pathologist_lic_no' => $orderRequest->pathologist_lic_no,
            ]);
        }

        return response()->json([], 404);
    }
    
    public function getTestForm(Request $request)
    {
        $testCode = $request->test_code;

        $viewName = match ($testCode) {
            'HEMA-T01' => 'results.forms.cbc',
            'HEMA-T02' => 'results.forms.cbc_ct_bt',
            'HEMA-T03' => 'results.forms.blood_typing',
            'CM-T01' => 'results.forms.fecalysis',
            'CM-T02' => 'results.forms.pregnancy_test',
            'CM-T03' => 'results.forms.urinalysis',
            'CC-T01' => 'results.forms.glucose',
            'CC-T02' => 'results.forms.bilirubin_test',
            'CC-T03' => 'results.forms.clinical_chemistry',
            'CC-T04' => 'results.forms.lipid_profile',
            'SERO-T01' => 'results.forms.dengue_test',
            'SERO-T02' => 'results.forms.serology',
            'SERO-T03' => 'results.forms.typhidot',
            'SERO-T04' => 'results.forms.covid19_test',
            'ELEC-T01' => 'results.forms.electrolytes',
            'PHY-ECG' => 'results.forms.ecg',
            default => 'results.forms.default',
        };

        $patientDetails = $request->only([
            'patient_name', 'patient_id', 'gender', 'age', 'birthday', 'test_program',
            'tests_ordered', 'sample_type', 'test_code', 'date_performed', 'date_released',
            'physician_full_name', 'medtech_full_name', 'medtech_lic_no',
            'pathologist_full_name', 'pathologist_lic_no',
        ]);

        return view($viewName, compact('patientDetails'));
    }

    public function validateResults(Request $request)
    {
        $testCode = $request->input('test_code');
        $results = $request->input('results', []);  // Capture results from form
        $gender = strtolower($request->input('gender'));  // Capture gender and convert to lowercase for gender-specific tests
        
        // Initialize validatedResults array
        $validatedResults = [];


        // Special case: Glucose test (CC-T01)
        if ($testCode === 'CC-T01') {
            $validatedResults[] = [
                'parameter' => 'Time',
                'value' => $results['Time'] ?? null,
                'range' => 'N/A',
                'flag' => '✔️',
                'unit' => '',
            ];
            $validatedResults[] = [
                'parameter' => 'Result',
                'value' => $results['Result'] ?? null,
                'range' => 'N/A',
                'flag' => '✔️',
                'unit' => '',
            ];
        }
        elseif ($testCode === 'HEMA-T01') {
    $ranges = [
        'RBC' => ['min' => 3.5, 'max' => 5.5, 'unit' => '10^12/L'],
        'Hemoglobin' => [
            'male' => ['min' => 135, 'max' => 175, 'unit' => 'g/L'],
            'female' => ['min' => 120, 'max' => 150, 'unit' => 'g/L']
        ],
        'Hematocrit' => [
            'male' => ['min' => 0.41, 'max' => 0.50, 'unit' => 'L/L'],
            'female' => ['min' => 0.36, 'max' => 0.45, 'unit' => 'L/L']
        ],
        'WBC' => ['min' => 4.0, 'max' => 11.0, 'unit' => 'x 10^9/L'],
        'Platelet Count' => ['min' => 150, 'max' => 400, 'unit' => 'x 10^9/L'],

        // Differential Count
        'Neutrophil' => ['min' => 50, 'max' => 70, 'unit' => '%'],
        'Lymphocyte' => ['min' => 20, 'max' => 40, 'unit' => '%'],
        'Monocyte' => ['min' => 3, 'max' => 12, 'unit' => '%'],
        'Stabs' => ['min' => 0, 'max' => 11, 'unit' => '%'],
        'Eosinophil' => ['min' => 0.5, 'max' => 5, 'unit' => '%'],
        'Basophil' => ['min' => 0.2, 'max' => 0.4, 'unit' => '%'],

        // RBC Indices
        'MCV' => ['min' => 80, 'max' => 100, 'unit' => 'fL'],
        'MCH' => ['min' => 27, 'max' => 34, 'unit' => 'pg'],
        'MCHC' => ['min' => 320, 'max' => 360, 'unit' => 'g/L'],
    ];

    foreach ($results as $parameter => $value) {
        $range = $ranges[$parameter] ?? null;

        if ($range) {
            if (isset($range['min'])) {
                $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => "{$range['min']} - {$range['max']}",
                    'flag' => $flag,
                    'unit' => $range['unit'],
                ];
            } elseif (is_array($range) && isset($range[$gender])) {
                // Gender-based range validation
                $genderRange = $range[$gender] ?? null;
                if ($genderRange) {
                    $flag = ($value >= $genderRange['min'] && $value <= $genderRange['max']) ? '✔️' : '⚠️';
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => "Male: {$range['male']['min']} - {$range['male']['max']} | Female: {$range['female']['min']} - {$range['female']['max']}",
                        'flag' => $flag,
                        'unit' => $genderRange['unit'],
                    ];
                }
            }
        } else {
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => 'N/A',
                'flag' => '⚠️',
                'unit' => 'N/A',
            ];
        }
    }
}

elseif ($testCode === 'HEMA-T02') {
    $ranges = [
        'RBC' => ['min' => 3.5, 'max' => 5.5, 'unit' => '10^12/L'],
        'Hemoglobin' => [
            'male' => ['min' => 135, 'max' => 175, 'unit' => 'g/L'],
            'female' => ['min' => 120, 'max' => 150, 'unit' => 'g/L']
        ],
        'Hematocrit' => [
            'male' => ['min' => 0.41, 'max' => 0.50, 'unit' => 'L/L'],
            'female' => ['min' => 0.36, 'max' => 0.45, 'unit' => 'L/L']
        ],
        'WBC' => ['min' => 4.0, 'max' => 11.0, 'unit' => 'x 10^9/L'],
        'Platelet Count' => ['min' => 150, 'max' => 400, 'unit' => 'x 10^9/L'],
        'Neutrophil' => ['min' => 50, 'max' => 70, 'unit' => '%'],
        'Lymphocyte' => ['min' => 20, 'max' => 40, 'unit' => '%'],
        'Monocyte' => ['min' => 3, 'max' => 12, 'unit' => '%'],
        'Stabs' => ['min' => 0, 'max' => 11, 'unit' => '%'],
        'Eosinophil' => ['min' => 0.5, 'max' => 5, 'unit' => '%'],
        'Basophil' => ['min' => 0.2, 'max' => 0.4, 'unit' => '%'],
        'MCV' => ['min' => 80, 'max' => 100, 'unit' => 'fL'],
        'MCH' => ['min' => 27, 'max' => 34, 'unit' => 'pg'],
        'MCHC' => ['min' => 320, 'max' => 360, 'unit' => 'g/L'],
        'Clotting Time' => ['min' => 5, 'max' => 15, 'unit' => 'Minutes'],
        'Bleeding Time' => ['min' => 1, 'max' => 3, 'unit' => 'Minutes'],
    ];

    foreach ($results as $parameter => $value) {
        $range = $ranges[$parameter] ?? null;

        if ($range) {
            if (isset($range['min'])) {
                // Non-gender-based range validation
                $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => "{$range['min']} - {$range['max']}",
                    'flag' => $flag,
                    'unit' => $range['unit'],
                ];
            } elseif (is_array($range) && isset($range['male']) && isset($range['female'])) {
                // Gender-based range validation (Fix to prevent duplicate "Male" and "Female")
                $maleRange = "{$range['male']['min']} - {$range['male']['max']}";
                $femaleRange = "{$range['female']['min']} - {$range['female']['max']}";

                $flag = ($value >= $range[$gender]['min'] && $value <= $range[$gender]['max']) ? '✔️' : '⚠️';

                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => "Male: {$maleRange} | Female: {$femaleRange}",  // ✅ FIXED DUPLICATE ISSUE
                    'flag' => $flag,
                    'unit' => $range[$gender]['unit'],
                ];
            }
        } else {
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => 'N/A',
                'flag' => '⚠️',
                'unit' => 'N/A',
            ];
        }
    }
}



        // Special case: Blood Typing test (HEMA-T03)
        elseif ($testCode === 'HEMA-T03') {
            $validatedResults[] = [
                'parameter' => 'ABO RH',
                'value' => $results['ABO RH'] ?? null,
                'range' => 'N/A',
                'flag' => '✔️',  // Always valid
                'unit' => '',  // No unit for blood typing
            ];
        }
        // Special case: Fecalysis (CM-T01)
        elseif ($testCode === 'CM-T01') {
            $ranges = [
                'WBC' => ['min' => 0, 'max' => 2, 'unit' => '/HPF'],
                'RBC' => ['min' => 0, 'max' => 2, 'unit' => '/HPF'],
                'Color' => ['range' => 'N/A', 'unit' => ''],
                'Consistency' => ['range' => 'N/A', 'unit' => ''],
                'Bacteria' => ['range' => 'N/A', 'unit' => ''],
                'Yeast cell' => ['range' => 'N/A', 'unit' => ''],
                'Fat globules' => ['range' => 'N/A', 'unit' => ''],
                'Occult Blood Test' => ['range' => 'N/A', 'unit' => ''],
            ];
        
            foreach ($results as $parameter => $value) {
                $range = $ranges[$parameter] ?? null;
        
                if ($range) {
                    if (isset($range['min'])) {
                        // Numerical range validation
                        $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                        $validatedResults[] = [
                            'parameter' => $parameter,
                            'value' => $value,
                            'range' => "{$range['min']} - {$range['max']}",
                            'flag' => $flag,
                            'unit' => $range['unit'],
                        ];
                    } else {
                        // If range is "N/A", remove Reference Range, Unit, and Flag
                        $validatedResults[] = [
                            'parameter' => $parameter,
                            'value' => $value,
                            'range' => '', // Remove "N/A"
                            'flag' => '',  // Remove flag
                            'unit' => '',  // Remove "N/A"
                        ];
                    }
                } else {
                    // If no predefined range, show warning flag
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => '', // Remove "N/A"
                        'flag' => '',  // Remove flag
                        'unit' => '',  // Remove "N/A"
                    ];
                }
            }
        }        
        
        // Special case: Pregnancy Test (CM-T02)
        elseif ($testCode === 'CM-T02') {
            $ranges = [
                'Urine' => ['range' => 'N/A', 'unit' => ''],
                'Plasma/Serum' => ['range' => 'N/A', 'unit' => ''],
            ];
    
            foreach ($results as $parameter => $value) {
                $range = $ranges[$parameter] ?? null;
                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => $range['range'] ?? 'N/A',
                    'flag' => '✔️',  // Assuming pregnancy test is binary (positive/negative)
                    'unit' => $range['unit'] ?? 'N/A',
                ];
            }
        }
       // Special case: Urinalysis (CM-T03)
       elseif ($testCode === 'CM-T03') {
        $ranges = [
            'Color' => ['expected' => ['Yellow', 'Straw', 'Amber'], 'unit' => ''],
            'Transparency' => ['expected' => ['Clear', 'Slightly Cloudy'], 'unit' => ''],
            'pH' => ['min' => 4.5, 'max' => 8.0, 'unit' => ''],
            'Specific Gravity' => ['min' => 1.005, 'max' => 1.030, 'unit' => ''],
            'Glucose' => ['expected' => ['Negative'], 'unit' => ''],
            'Protein' => ['expected' => ['Negative'], 'unit' => ''],
            'Epithelial Cells' => ['range' => 'N/A', 'unit' => '/LPF'],
            'Mucus Threads' => ['range' => 'N/A', 'unit' => ''],
            'Pus Cells' => ['min' => 0, 'max' => 2, 'unit' => '/HPF'],
            'RBC' => ['min' => 0, 'max' => 2, 'unit' => '/HPF'],
            'Bacteria' => ['range' => 'N/A', 'unit' => ''],
        ];
    
        foreach ($results as $parameter => $value) {
            $range = $ranges[$parameter] ?? null;
    
            if ($range) {
                if (isset($range['min'])) {
                    // Numerical range validation
                    $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => "{$range['min']} - {$range['max']}",
                        'flag' => $flag,
                        'unit' => $range['unit'],
                    ];
                } elseif (isset($range['expected'])) {
                    // Expected value validation
                    $flag = in_array($value, $range['expected']) ? '✔️' : '⚠️';
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => implode(', ', $range['expected']),
                        'flag' => $flag,
                        'unit' => $range['unit'],
                    ];
                } elseif ($range['range'] === 'N/A') {
                    // If "N/A", remove range, unit, and flag
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => '',
                        'flag' => '',
                        'unit' => '',
                    ];
                }
            } else {
                // If parameter is not found in predefined ranges, show warning
                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => '',
                    'flag' => '',
                    'unit' => '',
                ];
            }
        }
    }    

// Special case: Bilirubin (CC-T02)
elseif ($testCode === 'CC-T02') {
    $ranges = [
        'Total Bilirubin' => ['min' => 0.1, 'max' => 1.2, 'unit' => 'mg/dL'],
        'Direct Bilirubin' => ['min' => 0, 'max' => 0.2, 'unit' => 'mg/dL'],
        'Indirect Bilirubin' => ['min' => 0.2, 'max' => 0.8, 'unit' => 'mg/dL'],
    ];

    foreach ($results as $parameter => $value) {
        $range = $ranges[$parameter] ?? null;

        if ($range) {
            // Validate numerical range
            $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => "{$range['min']} - {$range['max']}",
                'flag' => $flag,
                'unit' => $range['unit'],
            ];
        } else {
            // If parameter is not defined in the ranges
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => 'N/A',
                'flag' => '⚠️',
                'unit' => 'N/A',
            ];
        }
    }
}

        // Special case: Clinical Chemistry (CC-T03)
        elseif ($testCode === 'CC-T03') {
            $ranges = [
                'Random Blood Sugar' => ['min' => 70, 'max' => 115, 'unit' => 'mg/dL'],
                'Fasting Blood Sugar' => ['min' => 70, 'max' => 100, 'unit' => 'mg/dL'],
                'Hemoglobin A1C' => ['min' => 4.0, 'max' => 6.0, 'unit' => '%'],
                'BUA' => [
                    'male' => ['min' => 3.5, 'max' => 7.2, 'unit' => 'mg/dL'],
                    'female' => ['min' => 2.6, 'max' => 6.0, 'unit' => 'mg/dL'],
                ],
                'BUN' => ['min' => 17, 'max' => 43, 'unit' => 'mg/dL'],
                'Creatinine' => [
                    'male' => ['min' => 0.7, 'max' => 1.3, 'unit' => 'mg/dL'],
                    'female' => ['min' => 0.6, 'max' => 1.1, 'unit' => 'mg/dL'],
                ],
                'Alkaline Phosphatase' => [
                    'male' => ['min' => 35, 'max' => 104, 'unit' => 'U/L'],
                    'female' => ['min' => 40, 'max' => 129, 'unit' => 'U/L'],
                ],
                'ALT/SGPT' => ['min' => 10, 'max' => 45, 'unit' => 'U/L'],
                'AST/SGOT' => ['min' => 10, 'max' => 37, 'unit' => 'U/L'],
                'Total Cholesterol' => ['min' => 140, 'max' => 200, 'unit' => 'mg/dL'],
                'Triglyceride' => ['min' => 67, 'max' => 157, 'unit' => 'mg/dL'],
                'HDL' => ['min' => 29, 'max' => 60, 'unit' => 'mg/dL'],
                'LDL' => ['min' => 57, 'max' => 130, 'unit' => 'mg/dL'],
                'VLDL' => ['min' => 0, 'max' => 30, 'unit' => 'mg/dL'],
            ];
            
    
            foreach ($results as $parameter => $value) {
                $range = $ranges[$parameter] ?? null;
    
                if (isset($range['male']) && isset($range['female'])) {
                    $genderRange = $range[$gender] ?? null;
                    if ($genderRange) {
                        $flag = ($value >= $genderRange['min'] && $value <= $genderRange['max']) ? '✔️' : '⚠️';
                        $validatedResults[] = [
                            'parameter' => $parameter,
                            'value' => $value,
                            'range' => "Male: {$range['male']['min']} - {$range['male']['max']} | Female: {$range['female']['min']} - {$range['female']['max']}",
                            'flag' => $flag,
                            'unit' => $genderRange['unit'],
                        ];
                    }
                                
                } elseif ($range) {
                    $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => "{$range['min']} - {$range['max']}",
                        'flag' => $flag,
                        'unit' => $range['unit'],
                    ];
                } else {
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => 'N/A',
                        'flag' => '⚠️',
                        'unit' => 'N/A',
                    ];
                }
            }
        }
        // Special case: Lipid Profile (CC-T04)
elseif ($testCode === 'CC-T04') {
    $ranges = [
        'Total Cholesterol' => ['min' => 140, 'max' => 200, 'unit' => 'mg/dL'],
        'Triglyceride' => ['min' => 67, 'max' => 157, 'unit' => 'mg/dL'],
        'HDL' => ['min' => 29, 'max' => 60, 'unit' => 'mg/dL'],
        'LDL' => ['min' => 57, 'max' => 130, 'unit' => 'mg/dL'],
        'VLDL' => ['min' => 0, 'max' => 30, 'unit' => 'mg/dL'],
    ];

    foreach ($results as $parameter => $value) {
        $range = $ranges[$parameter] ?? null;

        if ($range) {
            $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => "{$range['min']} - {$range['max']}",
                'flag' => $flag,
                'unit' => $range['unit'],
            ];
        } else {
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => 'N/A',
                'flag' => '⚠️',
                'unit' => 'N/A',
            ];
        }
    }
}
// Special case: Dengue Test (SERO-T01)
elseif ($testCode === 'SERO-T01') {
    // No reference ranges for Dengue Test, just collect the results
    foreach ($results as $parameter => $value) {
        $validatedResults[] = [
            'parameter' => $parameter,
            'value' => $value,
            'range' => 'N/A',  // No range for Dengue Test
            'flag' => null,    // No flag needed
            'unit' => '',      // No unit for Dengue Test
        ];
    }
}

// Special case: Serology (SERO-T02)
elseif ($testCode === 'SERO-T02') {
    $ranges = [
        'Troponin I' => ['min' => 0, 'max' => 0.3, 'unit' => 'ng/mL'],
        'Hepatitis B Surface Antigen' => ['range' => null, 'unit' => null],
        'Hepatitis B Surface Antibody' => ['range' => null, 'unit' => null],
        'Anti-HCV' => ['range' => null, 'unit' => null],
        'Anti-HAV' => ['range' => null, 'unit' => null],
        'HIV 1/2 Antibody (Screening)' => ['range' => null, 'unit' => null],
        'Syphilis (VDRL)' => ['range' => null, 'unit' => null],
        'TSH' => ['min' => 0.3, 'max' => 4.2, 'unit' => 'mIU/L'],
        'FT3' => ['min' => 2.8, 'max' => 7.1, 'unit' => 'pmol/L'],
        'FT4' => ['min' => 12, 'max' => 22, 'unit' => 'pmol/L'],
        'T3' => ['min' => 1.23, 'max' => 3.07, 'unit' => 'nmol/L'],
        'T4' => ['min' => 66, 'max' => 181, 'unit' => 'nmol/L'],
        'CEA' => ['min' => 0, 'max' => 5.0, 'unit' => 'ng/mL'],
        'CA19-9' => ['min' => 0, 'max' => 27.4, 'unit' => 'U/mL'],
        'PSA' => ['min' => 0, 'max' => 4, 'unit' => 'ng/mL'],
    ];

    foreach ($results as $parameter => $value) {
        $range = $ranges[$parameter] ?? null;

        if ($range) {
            if (isset($range['min'])) {
                // Numerical validation
                $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => "{$range['min']} - {$range['max']}",
                    'flag' => $flag,
                    'unit' => $range['unit'],
                ];
            } else {
                // No numerical validation (range is null)
                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => '',
                    'flag' => '', // No flag if no reference range
                    'unit' => '', // No unit if not applicable
                ];
            }
        } else {
            // Unknown parameter (fallback case)
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => '',
                'flag' => '', // No flag if no reference range
                'unit' => '',
            ];
        }
    }
}

// Special case: Typhidot (SERO-T03)
elseif ($testCode === 'SERO-T03') {
    $parameters = ['IgG Antibody', 'IgM Antibody'];

    foreach ($results as $parameter => $value) {
        if (in_array($parameter, $parameters)) {
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => 'N/A',  // No range for Typhidot
                'flag' => '✔️',    // No flag needed, but ✔️ indicates it's present
                'unit' => '',       // No unit for Typhidot
            ];
        }
    }
}
// Special case: SARS-CoV-2 (COVID-19) RAPID ANTIGEN TEST (SERO-T04)
elseif ($testCode === 'SERO-T04') {
    $parameters = [
        'SARS-CoV-2 (COVID-19) RAPID ANTIGEN TEST',
        'Brand Used and Principle'
    ];

    foreach ($results as $parameter => $value) {
        if (in_array($parameter, $parameters)) {
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => 'N/A',  // No range for COVID-19 Antigen Test
                'flag' => '✔️',    // Optional flag indicating valid data
                'unit' => '',       // No unit for COVID-19 Antigen Test
            ];
        }
    }
}
// Special case: Electrolytes (ELEC-T01)
elseif ($testCode === 'ELEC-T01') {
    $ranges = [
        'Sodium' => ['min' => 135, 'max' => 145, 'unit' => 'mmol/L'],
        'Potassium' => ['min' => 3.4, 'max' => 5.0, 'unit' => 'mmol/L'],
        'Chloride' => ['min' => 98, 'max' => 107, 'unit' => 'mmol/L'],
        'Ionized Calcium' => ['min' => 1.16, 'max' => 1.38, 'unit' => 'mmol/L'],
        'Total Calcium' => ['min' => 8.6, 'max' => 10.3, 'unit' => 'mg/dL'],
        
        // Gender-Specific Ranges
        'Magnesium' => [
            'male' => ['min' => 1.8, 'max' => 2.6, 'unit' => 'mg/dL'],
            'female' => ['min' => 1.9, 'max' => 2.5, 'unit' => 'mg/dL']
        ],
    ];

    foreach ($results as $parameter => $value) {
        $range = $ranges[$parameter] ?? null;

        if ($range) {
            if (isset($range['min'])) {
                // Non-Gender-Specific Parameters
                $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => "{$range['min']} - {$range['max']}",
                    'flag' => $flag,
                    'unit' => $range['unit'],
                ];
            } elseif (is_array($range) && isset($range['male']) && isset($range['female'])) {
                // Gender-Specific Parameters (e.g., Magnesium)
                $maleRange = "{$range['male']['min']} - {$range['male']['max']}";
                $femaleRange = "{$range['female']['min']} - {$range['female']['max']}";

                $flag = ($value >= $range[$gender]['min'] && $value <= $range[$gender]['max']) ? '✔️' : '⚠️';

                $validatedResults[] = [
                    'parameter' => $parameter,
                    'value' => $value,
                    'range' => "Male: {$maleRange} | Female: {$femaleRange}",
                    'flag' => $flag,
                    'unit' => $range[$gender]['unit'],
                ];
            }
        } else {
            $validatedResults[] = [
                'parameter' => $parameter,
                'value' => $value,
                'range' => 'N/A',
                'flag' => '⚠️',
                'unit' => 'N/A',
            ];
        }
    }
}

else if ($testCode === 'PHY-ECG') {
    $ecgParameters = [
        'MDC_ECG_LEAD_I' => 'mV',
        'MDC_ECG_LEAD_II' => 'mV',
        'MDC_ECG_LEAD_III' => 'mV',
        'ECG_HEART_RATE' => 'bpm',
        'ECG_TTHOR_RESP_RATE' => 'breaths/min',
    ];

    foreach ($ecgParameters as $parameter => $unit) {
        $validatedResults[] = [
            'parameter' => $parameter,
            'value' => $results[$parameter] ?? '',
            'unit' => $unit,
        ];
        
    }
}



        // Other test codes (HEMA-T01, CC-T02, ELEC-T01, etc.)
        else {
            $ranges = [
                'HEMA-T01' => [
                    'RBC' => ['min' => 3.5, 'max' => 5.5, 'unit' => '10^12/L'],
                    'Hemoglobin' => [
                        'male' => ['min' => 135, 'max' => 175, 'unit' => 'g/L'],
                        'female' => ['min' => 120, 'max' => 150, 'unit' => 'g/L']
                    ],
                    'Hematocrit' => [
                        'male' => ['min' => 0.41, 'max' => 0.50, 'unit' => 'L/L'],
                        'female' => ['min' => 0.36, 'max' => 0.45, 'unit' => 'L/L']
                    ],
                    'WBC' => ['min' => 4.0, 'max' => 11.0, 'unit' => 'x 10^9/L'],
                    'Platelet Count' => ['min' => 150, 'max' => 400, 'unit' => 'x 10^9/L'],
                    'Neutrophil' => ['min' => 50, 'max' => 70, 'unit' => '%'],
                    'Lymphocyte' => ['min' => 20, 'max' => 40, 'unit' => '%'],
                    'Monocyte' => ['min' => 3, 'max' => 12, 'unit' => '%'],
                    'Stabs' => ['min' => 0, 'max' => 11, 'unit' => '%'],
                    'Eosinophil' => ['min' => 0.5, 'max' => 5, 'unit' => '%'],
                    'Basophil' => ['min' => 0.2, 'max' => 0.4, 'unit' => '%'],
                    'MCV' => ['min' => 80, 'max' => 100, 'unit' => 'fL'],
                    'MCH' => ['min' => 27, 'max' => 34, 'unit' => 'pg'],
                    'MCHC' => ['min' => 320, 'max' => 360, 'unit' => 'g/L'],
                ],
                'CC-T02' => [
                    'Total Bilirubin' => ['min' => 0.1, 'max' => 1.2, 'unit' => 'mg/dL'],
                    'Direct Bilirubin' => ['min' => 0, 'max' => 0.2, 'unit' => 'mg/dL'],
                    'Indirect Bilirubin' => ['min' => 0.2, 'max' => 0.8, 'unit' => 'mg/dL'],
                ],
                'ELEC-T01' => [
                    'Sodium' => ['min' => 135, 'max' => 145, 'unit' => 'mmol/L'],
                    'Potassium' => ['min' => 3.4, 'max' => 5.0, 'unit' => 'mmol/L'],
                    'Chloride' => ['min' => 98, 'max' => 107, 'unit' => 'mmol/L'],
                    'Ionized Calcium' => ['min' => 1.16, 'max' => 1.38, 'unit' => 'mmol/L'],
                    'Total Calcium' => ['min' => 8.6, 'max' => 10.3, 'unit' => 'mg/dL'],
                    'Magnesium' => ['min' => 1.8, 'max' => 2.6, 'unit' => 'mg/dL'],
                ],
            ];
    
            foreach ($results as $parameter => $value) {
                $range = $ranges[$testCode][$parameter] ?? null;
        
                if ($range) {
                    if (isset($range['min'])) {
                        $flag = ($value >= $range['min'] && $value <= $range['max']) ? '✔️' : '⚠️';
                        $validatedResults[] = [
                            'parameter' => $parameter,
                            'value' => $value,
                            'range' => "{$range['min']} - {$range['max']}",
                            'flag' => $flag,
                            'unit' => $range['unit'],
                        ];
                    } elseif (is_array($range) && isset($range[$gender])) {
                        $genderRange = $range[$gender] ?? null;
                        if ($genderRange) {
                            $flag = ($value >= $genderRange['min'] && $value <= $genderRange['max']) ? '✔️' : '⚠️';
                            $validatedResults[] = [
                                'parameter' => $parameter,
                                'value' => $value,
                                'range' => "{$genderRange['min']} - {$genderRange['max']}",
                                'flag' => $flag,
                                'unit' => $genderRange['unit'],
                            ];
                        }
                    } else {
                        $validatedResults[] = [
                            'parameter' => $parameter,
                            'value' => $value,
                            'range' => 'N/A',
                            'flag' => '✔️',
                            'unit' => $range['unit'] ?? 'N/A',
                        ];
                    }
                } else {
                    $validatedResults[] = [
                        'parameter' => $parameter,
                        'value' => $value,
                        'range' => 'N/A',
                        'flag' => '⚠️',
                        'unit' => 'N/A',
                    ];
                }
            }
        }
    
        // Pass the patient details and validated results to the appropriate validation view
        $patientDetails = $request->only([
            'patient_name', 'patient_id', 'gender', 'age', 'birthday', 'test_program',
            'tests_ordered', 'sample_type', 'test_code', 'date_performed', 'date_released',
            'physician_full_name', 'medtech_full_name', 'medtech_lic_no',
            'pathologist_full_name', 'pathologist_lic_no',
        ]);

    
        $viewName = match ($testCode) {
            'HEMA-T01' => 'results.validation.cbc',
            'HEMA-T02' => 'results.validation.cbc_ct_bt',
            'HEMA-T03' => 'results.validation.blood_typing',
            'CM-T01' => 'results.validation.fecalysis',
            'CM-T02' => 'results.validation.pregnancy',
            'CM-T03' => 'results.validation.urinalysis',
            'CC-T01' => 'results.validation.glucose',
            'CC-T02' => 'results.validation.bilirubin',
            'CC-T03' => 'results.validation.clinical_chemistry',
            'CC-T04' => 'results.validation.lipid_profile', 
            'SERO-T01' => 'results.validation.dengue_test',
            'SERO-T02' => 'results.validation.serology',
            'SERO-T03' => 'results.validation.typhidot',
            'SERO-T04' => 'results.validation.covid19_test',
            'ELEC-T01' => 'results.validation.electrolytes',
            'PHY-ECG' => 'results.validation.ecg',
            default => 'results.validation.default',
        };
    
        return view($viewName, [
            'validatedResults' => $validatedResults,
            'patientDetails' => $patientDetails,
        ]);
    }
    
    public function saveValidationAndGeneratePDF(Request $request)
    {
        // Validate incoming data
        $validatedData = $request->validate([
            'time_released' => 'nullable|date_format:H:i', // Allow null or valid time format
            'patientDetails' => 'required|array',
            'validatedResults' => 'required|array',
            'remarks' => 'nullable|string',
        ]);

        // Default time_released to current time if not provided
        $timeReleased = $validatedData['time_released'] ?? now()->format('H:i');
        $patientDetails = array_merge($validatedData['patientDetails'], ['time_released' => $timeReleased]);
        $validatedResults = $validatedData['validatedResults'];
        $remarks = $validatedData['remarks'] ?? '';

        // Retrieve the related order request
        $orderRequest = OrderRequest::where('patient_id', $patientDetails['patient_id'])->first();
        if (!$orderRequest) {
            return back()->with('error', 'Order request not found for the given patient.');
        }

        // Generate PDF
        $pdfTemplate = $this->resolvePdfTemplate($patientDetails['test_code']);
        $pdf = Pdf::loadView($pdfTemplate, compact('patientDetails', 'validatedResults', 'remarks', 'timeReleased'));

        // Save PDF
        $pdfPath = $this->savePdf($pdf, $patientDetails, $patientDetails['test_code']);
        if (!$pdfPath) {
            return back()->with('error', 'Failed to generate PDF.');
        }

        // Create the result record
        $result = Result::create([
            'order_request_id' => $orderRequest->id,
            'user_id' => auth()->id(),
            'machine' => $request->input('machine'),
            'patient_name' => $patientDetails['patient_name'],
            'patient_id' => $patientDetails['patient_id'],
            'pdf_file' => $pdfPath,
            'pdf_file_path' => asset($pdfPath),
            'date_released' => now()->format('Y-m-d'),
            'time_released' => $timeReleased,
            'results' => json_encode($validatedResults),
            'remarks' => $remarks,
        ]);

        // Log the activity
        ActivityLog::create([
            'action' => 'Generated PDF for patient: ' . $patientDetails['patient_name'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('patient_log')->with('success', 'Results validated and PDF generated successfully.');
    }

    public function deleteResult($id)
    {
        $result = Result::findOrFail($id);
        $pdfPath = public_path($result->pdf_file);

        if (File::exists($pdfPath)) {
            File::delete($pdfPath);
        }

        $result->delete();

        // Log the activity
        ActivityLog::create([
            'action' => 'Deleted result for patient: ' . $result->patient_name,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('patient_log')->with('success', 'Result and corresponding PDF deleted successfully.');
    }

    public function patientLog()
    {
        $patientLogs = Result::with('orderRequest')->get();

        // Log the activity
        ActivityLog::create([
            'action' => 'Viewed patient logs',
            'user_id' => auth()->id(),
        ]);

        if (auth()->user()->usertype === 'admin') {
            return view('admin.results.patient_log', compact('patientLogs'));
        }

        return view('results.patient_log', compact('patientLogs'));
    }

    public function downloadPDF($id)
    {
        $result = Result::findOrFail($id);
        $pdfPath = public_path($result->pdf_file);

        if (File::exists($pdfPath)) {
            // Log the activity
            ActivityLog::create([
                'action' => 'Downloaded PDF for patient: ' . $result->patient_name,
                'user_id' => auth()->id(),
            ]);

            return response()->download($pdfPath);
        }

        return back()->with('error', 'PDF file not found.');
    }

    private function resolvePdfTemplate($testCode)
    {
        return match ($testCode) {
            'HEMA-T01' => 'results.validation.pdfs.cbc_pdf',
            'HEMA-T02' => 'results.validation.pdfs.cbc_ct_bt_pdf',
            'HEMA-T03' => 'results.validation.pdfs.blood_typing_pdf',
            'CM-T01' => 'results.validation.pdfs.fecalysis_pdf',
            'CM-T02' => 'results.validation.pdfs.pregnancy_pdf',
            'CM-T03' => 'results.validation.pdfs.urinalysis_pdf',
            'CC-T01' => 'results.validation.pdfs.glucose_pdf',
            'CC-T02' => 'results.validation.pdfs.bilirubin_pdf',
            'CC-T03' => 'results.validation.pdfs.clinical_chemistry_pdf',
            'CC-T04' => 'results.validation.pdfs.lipid_profile_pdf',
            'SERO-T01' => 'results.validation.pdfs.dengue_test_pdf',
            'SERO-T02' => 'results.validation.pdfs.serology_pdf',
            'SERO-T03' => 'results.validation.pdfs.typhidot_pdf',
            'SERO-T04' => 'results.validation.pdfs.covid19_test_pdf',
            'ELEC-T01' => 'results.validation.pdfs.electrolytes_pdf',
            'PHY-ECG' => 'results.validation.pdfs.ecg_pdf',
            default => 'results.validation.pdfs.default_pdf',
        };
    }

    private function savePdf($pdf, $patientDetails, $testCode)
    {
        $directory = public_path('pdfs');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $patientDetails['patient_name']);
        $fileName = "Result_{$sanitizedName}_{$testCode}.pdf";
        $filePath = "pdfs/{$fileName}";

        try {
            $pdf->save(public_path($filePath));
        } catch (\Exception $e) {
            Log::error('Failed to save PDF: ' . $e->getMessage());
            return null;
        }

        return File::exists(public_path($filePath)) ? $filePath : null;
    }
}

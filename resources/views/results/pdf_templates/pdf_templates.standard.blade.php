<!DOCTYPE html>
<html>
<head>
    <title>Validation Results</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Validation Results</h2>
    
    <!-- Patient Details -->
    <h3>Patient Details</h3>
    <table>
        <tr>
            <th>Name</th>
            <td>{{ $patientDetails['patient_name'] }}</td>
            <th>Age</th>
            <td>{{ $patientDetails['age'] }}</td>
        </tr>
        <tr>
            <th>Gender</th>
            <td>{{ $patientDetails['gender'] }}</td>
            <th>Physician</th>
            <td>{{ $patientDetails['physician_full_name'] }}</td>
        </tr>
        <tr>
            <th>Sample Type</th>
            <td>{{ $patientDetails['sample_type'] }}</td>
            <th>Date Performed</th>
            <td>{{ \Carbon\Carbon::parse($patientDetails['date_performed'])->format('F j, Y') }}</td>
        </tr>
        <tr>
            <th>Date Released</th>
            <td>{{ \Carbon\Carbon::parse($patientDetails['date_released'])->format('F j, Y') }}</td>
            <th>Time Released</th>
            <td>{{ $patientDetails['time_released'] }}</td>
        </tr>
    </table>

    <!-- Validated Results -->
    <h3>Test Results</h3>
    <table>
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Result</th>
                <th>Unit</th>
                <th>Reference Range</th>
                <th>Flag</th>
            </tr>
        </thead>
        <tbody>
            @foreach($validatedResults as $result)
            <tr>
                <td>{{ $result['parameter'] }}</td>
                <td>{{ $result['value'] }}</td>
                <td>{{ $result['unit'] }}</td>
                <td>{{ $result['range'] }}</td>
                <td>{{ $result['flag'] == '✔️' ? 'Normal' : 'Abnormal' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

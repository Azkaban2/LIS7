<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilirubin Test - PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 14px;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 8px;
        }

        .header p2 {
            margin: 0;
            font-size: 8px;
            color: #008cff;
        }

        .header img {
            width: 70px;
            height: 60px;
            margin-bottom: 5px;
        }

        .info-section {
            width: 100%;
            margin-bottom: 10px;
            display: table;
        }

        .left-info, .right-info {
            display: table-cell;
            vertical-align: top;
            padding-right: 10px;
        }

        .left-info {
            width: 50%;
        }

        .right-info {
            width: 50%;
        }

        .info-section div {
            font-size: 10px;
        }

        .left-info p, .right-info p {
            margin: 1px 0;
        }

        .info-section div span {
            font-weight: bold;
        }

        .separator-line {
            width: 100%;
            border-top: 1px solid black;
            margin: 5px 0;
        }

        .table-section {
            width: 100%;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #d4e1f5;
            text-align: center;
            font-weight: bold;
            padding: 3px;
        }

        td {
            text-align: center;
            padding: 4px;
        }

        .remarks-section {
            margin-top: 10px;
            font-size: 10px;
        }

        .signature-section {
            width: 100%;
            margin-top: 20px;
            display: table;
        }

        .left-signature, .right-signature {
            display: table-cell;
            vertical-align: top;
            text-align: center;
            width: 50%;
            font-weight: bold;
        }

        .signature-line {
            border-top: 1px solid black;
            display: block;
            margin: 3px auto;
            width: 150px;
        }

        .footer-text {
            text-align: center;
            font-size: 8px;
            color: #555;
            margin-top: 5px;
        }

        .footer-text p {
            margin: 3px 0;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <img src="{{ public_path('logo/eagles.png') }}" alt="Logo">
        <h1>EPH Multi-Specialty and Diagnostic Center Inc.</h1>
        <p>Because We Saw The Need</p>
        <p>GF Yap Bldg., Tibanga Highway, Santiago, Iligan City</p>
        <p2>229-6558 | 0917-125-7121 | eph.tibanga2020@gmail.com</p2>
        <h2 style="text-transform: uppercase; margin-top: 5px;">Bilirubin Test</h2>
    </div>

    <!-- Separator Line -->
    <div class="separator-line"></div>

    <!-- Patient Info Section -->
    <div class="info-section">
        <div class="left-info">
            <p><span>Name:</span> {{ $patientDetails['patient_name'] }}</p>
            <p><span>Age:</span> {{ $patientDetails['age'] }}</p>
            <p><span>Gender:</span> {{ $patientDetails['gender'] }}</p>
            <p><span>Birthday:</span> {{ isset($patientDetails['birthday']) ? \Carbon\Carbon::parse($patientDetails['birthday'])->format('F j, Y') : '' }}</p>
            <p><span>Physician:</span> {{ $patientDetails['physician_full_name'] }}</p>
        </div>
        <div class="right-info">
            <p><span>Sample Submitted:</span> {{ $patientDetails['sample_type'] }}</p>
            <p><span>Date Performed:</span> {{ isset($patientDetails['date_performed']) ? \Carbon\Carbon::parse($patientDetails['date_performed'])->format('F j, Y') : '' }}</p>
            <p><span>Date Released:</span> {{ isset($patientDetails['date_released']) ? \Carbon\Carbon::parse($patientDetails['date_released'])->format('F j, Y') : '' }}</p>
            <p><span>Time Released:</span> {{ isset($patientDetails['time_released']) ? \Carbon\Carbon::parse($patientDetails['time_released'])->format('h:i A') : 'N/A' }}</p>
        </div>
    </div>

    <!-- Bilirubin Test Table Section -->
    <h3 style="text-align: center;">Bilirubin Test Results</h3>
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Result</th>
                    <th>Unit</th>
                    <th>Reference Range</th>
                </tr>
            </thead>
            <tbody>
                @foreach($validatedResults as $result)
                <tr>
                    <td>{{ $result['parameter'] }}</td>
                    <td>{{ $result['value'] }}</td>
                    <td>{{ $result['unit'] }}</td>
                    <td>{{ $result['range'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Remarks Section -->
    <div class="remarks-section">
        <h4>Remarks:</h4>
        <p>{{ $remarks }}</p>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="left-signature">
            <p>{{ $patientDetails['medtech_full_name'] ?? 'N/A' }}, RMT Lic. No. {{ $patientDetails['medtech_lic_no'] ?? 'N/A' }}</p>
            <span class="signature-line"></span>
            <p>Medical Technologist</p>
        </div>
        <div class="right-signature">
            <p>{{ $patientDetails['pathologist_full_name'] ?? 'N/A' }}, MD Lic. No. {{ $patientDetails['pathologist_lic_no'] ?? 'N/A' }}</p>
            <span class="signature-line"></span>
            <p>Pathologist</p>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer-text">
        <p>This laboratory test result is only part of the overall assessment of the patient's condition.</p>
        <p>Please correlate with clinical findings and other parameters for a comprehensive analysis.</p>
        <p>All results are best explained by the requesting physician.</p>
    </div>

</body>
</html>

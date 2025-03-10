<!DOCTYPE html>
<html>
<head>
    <title>Blood Typing Validation Result</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #d1e7fd; }
        h2, h3 { text-align: center; }
    </style>
</head>
<body>
    <h2>EPH MULTI-SPECIALTY AND DIAGNOSTIC CENTER INC.</h2>
    <p style="text-align: center;">
        Because We Saw The Need. <br>
        G/F YAP BLDG., Tibanga Highway, Santiago, Iligan City <br>
        Contact: 229-6558 | 0917-125-7121 | eph.tibanga2020@gmail.com
    </p>

    <h3>BLOOD TYPING</h3>

    <table>
        <tr>
            <td><strong>Name:</strong> {{ $patientDetails['patient_name'] }}</td>
            <td><strong>Sample Submitted:</strong> {{ $patientDetails['sample_type'] }}</td>
        </tr>
        <tr>
            <td><strong>Age:</strong> {{ $patientDetails['age'] }}</td>
            <td><strong>Date Performed:</strong> {{ $patientDetails['date_performed'] }}</td>
        </tr>
        <tr>
            <td><strong>Gender:</strong> {{ $patientDetails['gender'] }}</td>
            <td><strong>Date Released:</strong> {{ $patientDetails['date_released'] }}</td>
        </tr>
        <tr>
            <td><strong>Birthday:</strong> {{ $patientDetails['birthday'] }}</td>
            <td><strong>Time Released:</strong> {{ $patientDetails['time_released'] }}</td>
        </tr>
        <tr>
            <td><strong>Physician:</strong> {{ $patientDetails['physician_full_name'] }}</td>
        </tr>
    </table>

    <h3>Blood Typing Result</h3>
    <table>
        <thead>
            <tr>
                <th>TEST</th>
                <th>RESULT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ABO RH</td>
                <td>{{ $validatedResults['ABO_RH'] }}</td>
            </tr>
        </tbody>
    </table>

    <p>
        <strong>Medical Technologist:</strong> Nadjer S. Ananggo, RMT Lic. No. 0081182<br>
        <strong>Pathologist:</strong> CHONILO O. RUIZ, MD., FPSP Lic. No. 037644
    </p>

    <p style="text-align: center;">
        This laboratory test result is only part of the overall assessment of the patient's condition. 
        Please correlate with clinical findings and other parameters for a comprehensive analysis. 
        All results are best explained by the requesting physician.
    </p>
</body>
</html>

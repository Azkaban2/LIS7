@extends('layouts.app')

@section('content')
<div class="flex">
        {{-- Sidebar --}}
        <div class="fixed left-0 top-0 w-64 h-full bg-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
            <a href="{{ route('dashboard') }}" class="flex items-center pb-4 border-b border-b-white">
                <img src="{{ asset('logo/eagles.png') }}" class="h-15 w-20" alt="Eagles Logo">
                <span class="text-lg font-bold text-white ml-3">Laboratory Information System</span>
            </a>

            <ul class="mt-4">
                <li class="mb-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-home-2-line mr-3 text-lg"></i>
                        <span class="text-sm">Main Dashboard</span>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('order-requests.create') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-instance-line mr-3 text-lg"></i>
                        <span class="text-sm">Add Request</span>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-instance-line mr-3 text-lg"></i>
                        <span class="text-sm">Request Log</span>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('results.instrument_import') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-instance-line mr-3 text-lg"></i>
                        <span class="text-sm">Results</span>
                    </a>
                </li>
                <li class="mb-1 group">
                    <a href="#" class="flex items-center py-2 px-4 text-white bg-red-600 rounded-md">
                        <i class="ri-flashlight-line mr-3 text-lg"></i>
                        <span class="flex-1 text-sm">Validation</span>
                    </a>
                </li>
                <li class="mb-1 active">
                    <a href="{{ route('patient_log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-file-list-line mr-3 text-lg"></i>
                        <span class="text-sm">Patient Log</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
        {{-- end: Sidebar --}}

 <!-- CBC Validation Content -->
 <div class="ml-64 flex-1 p-6 container mx-auto mt-10">
    <div class="bg-white shadow-md rounded p-6">
        <h3 class="text-lg font-semibold text-center mb-4">Validation - Complete Blood Count (CBC)</h3>

        <!-- Display Patient Details -->
        <div class="mb-4 bg-gray-100 p-6 rounded shadow-md">
            <div class="flex justify-between">
                <div class="w-1/2">
                    <p class="mb-1"><strong>Name:</strong> {{ $patientDetails['patient_name'] }}</p>
                    <p class="mb-1"><strong>Age:</strong> {{ $patientDetails['age'] }}</p>
                    <p class="mb-1"><strong>Gender:</strong> {{ $patientDetails['gender'] }}</p>
                    <p class="mb-1"><strong>Birthday:</strong> {{ isset($patientDetails['birthday']) ? \Carbon\Carbon::parse($patientDetails['birthday'])->format('F j, Y') : '' }}</p>
                    <p class="mb-1"><strong>Physician:</strong> {{ $patientDetails['physician_full_name'] }}</p>
                </div>

                <div class="w-1/2">
                    <p class="mb-1"><strong>Sample Submitted:</strong> {{ $patientDetails['sample_type'] }}</p>
                    <p><span>Date Performed:</span> {{ $patientDetails['date_performed'] ? \Carbon\Carbon::parse($patientDetails['date_performed'])->format('F j, Y') : 'N/A' }}</p>
                    <p><span>Date Released:</span> {{ $patientDetails['date_released'] ? \Carbon\Carbon::parse($patientDetails['date_released'])->format('F j, Y') : 'N/A' }}</p>
                    
                   <!-- Time Released Input -->
<div class="flex items-center">
    <p class="mb-1"><strong>Time Released:</strong></p>
    <input 
        type="time" 
        id="time-released" 
        name="time_released" 
        value="{{ old('time_released', now()->format('H:i')) }}"
        class="ml-2 px-2 py-1 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 w-32">                    
</div>

                </div>
            </div>
        </div>

   <!-- CBC Validation Table & PDF Generation Form -->
<form action="{{ route('generate-pdf') }}" method="POST">
    @csrf

    {{-- Include hidden inputs for patient details --}}
    @foreach($patientDetails as $key => $value)
        <input type="hidden" name="patientDetails[{{ $key }}]" value="{{ $value }}">
    @endforeach

    {{-- Table with parameters and input fields --}}
    <table class="w-full border-collapse">
        <thead>
            <tr class="border-b bg-gray-100">
                <th class="text-center py-2">Parameter</th>
                <th class="text-center py-2">Flag</th>
                <th class="text-center py-2">Result</th>
                <th class="text-center py-2">Unit</th>
                <th class="text-center py-2">Reference Range</th>

            </tr>
        </thead>
        <tbody>
            @foreach($validatedResults as $index => $result)
    
                {{-- DIFFERENTIAL COUNT HEADER --}}
                @if($result['parameter'] === 'Neutrophil') 
                    <tr class="border-b bg-gray-200">
                        <td colspan="5" class="py-2 text-left font-semibold text-gray-700 px-4">
                            DIFFERENTIAL COUNT
                        </td>
                    </tr>
                @endif
    
                {{-- RBC INDICES HEADER --}}
                @if($result['parameter'] === 'MCV')
                    <tr class="border-b bg-gray-200">
                        <td colspan="5" class="py-2 text-left font-semibold text-gray-700 px-4">
                            RBC INDICES
                        </td>
                    </tr>
                @endif
    
                <tr class="border-b">
                    <td class="py-2 text-center">{{ $result['parameter'] }}</td>
                    <td class="py-2 text-center">
                        <span class="flag-indicator {{ $result['flag'] == '✔️' ? 'text-green-500' : 'text-red-500' }}">
                            {{ $result['flag'] }}
                        </span>
                    </td>
                    <td class="py-2 text-center">
                        <input type="text" 
                            name="validatedResults[{{ $index }}][value]" 
                            value="{{ $result['value'] }}" 
                            class="w-full text-center border rounded result-input"
                            data-index="{{ $index }}"
                            data-parameter="{{ $result['parameter'] }}"
                            data-range="{{ $result['range'] }}"
                            data-unit="{{ $result['unit'] }}"
                            data-gender="{{ strtolower($patientDetails['gender']) }}">
                    </td>
                    <td class="py-2 text-center">{{ $result['unit'] }}</td>
                    <td class="py-2 text-center">{{ $result['range'] }}</td>
                </tr>
    
                {{-- Keep hidden inputs unchanged for form submission --}}
                <input type="hidden" name="validatedResults[{{ $index }}][parameter]" value="{{ $result['parameter'] }}">
                <input type="hidden" name="validatedResults[{{ $index }}][unit]" value="{{ $result['unit'] }}">
                <input type="hidden" name="validatedResults[{{ $index }}][range]" value="{{ $result['range'] }}">
            @endforeach
        </tbody>
    </table>
    

            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Finalize</button>
            </div>
        </form>
    </div>
</div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const resultInputs = document.querySelectorAll(".result-input");

        resultInputs.forEach(input => {
            input.addEventListener("input", function () {
                const value = parseFloat(this.value);
                const row = this.closest("tr"); 
                const parameter = this.getAttribute("data-parameter"); 
                const gender = this.getAttribute("data-gender"); 
                let range = this.getAttribute("data-range"); 
                let flagSpan = row.querySelector(".flag-indicator");

                let min, max;
                let isValid = false;

                // Handle gender-specific ranges correctly
                if (range.includes("Male:") && range.includes("Female:")) {
                    let maleRange = range.match(/Male:\s*(\d+\.?\d*)\s*-\s*(\d+\.?\d*)/);
                    let femaleRange = range.match(/Female:\s*(\d+\.?\d*)\s*-\s*(\d+\.?\d*)/);

                    if (gender === "male" && maleRange) {
                        min = parseFloat(maleRange[1]);
                        max = parseFloat(maleRange[2]);
                    } else if (gender === "female" && femaleRange) {
                        min = parseFloat(femaleRange[1]);
                        max = parseFloat(femaleRange[2]);
                    }
                } else {
                    let rangeParts = range.split("-").map(part => part.trim());
                    min = parseFloat(rangeParts[0]);
                    max = parseFloat(rangeParts[1]);
                }

                // Validate input value
                if (!isNaN(value) && !isNaN(min) && !isNaN(max)) {
                    isValid = value >= min && value <= max;
                }

                // Update the flag dynamically
                if (flagSpan) {
                    flagSpan.textContent = isValid ? '✔️' : '⚠️';
                    flagSpan.classList.toggle("text-green-500", isValid);
                    flagSpan.classList.toggle("text-red-500", !isValid);
                }
            });
        });
    });
</script>
    
@endsection
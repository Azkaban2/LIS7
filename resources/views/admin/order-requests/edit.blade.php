@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Order Request</h1>

    {{-- Sidebar --}}
    <div class="fixed left-0 top-0 w-64 h-full bg-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
        <a href="dashboard" class="flex items-center pb-4 border-b border-b-white">
            <img src="{{ asset('logo/eagles.png') }}" class="h-15 w-20" alt="Eagles Logo">
            <span class="text-lg font-bold text-white ml-3">Laboratory Information System</span>
        </a>

        <ul class="mt-4">
            <li class="mb-1 group relative">
                <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                    <i class="ri-instance-line mr-3 text-lg"></i>
                    <span class="text-sm">Main Dashboard</span>
                    <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                </a>
            </li>

            <li class="mb-1 group active">
                <a href="#" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class="ri-home-2-line mr-3 text-lg"></i>
                    <span class="text-sm">Edit Request</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
    {{-- Sidebar End --}}

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <style>
        #progress-bar {
            counter-reset: step;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #progress-bar li {
            list-style-type: none;
            flex: 1;
            text-align: center;
            position: relative;
        }

        #progress-bar li:before {
            content: counter(step);
            counter-increment: step;
            width: 2.5rem;
            height: 2.5rem;
            line-height: 2.5rem;
            display: block;
            font-size: 1rem;
            color: #333;
            background: white;
            border-radius: 50%;
            margin: 0 auto 1rem auto;
            border: 2px solid #ddd;
        }

        #progress-bar li:after {
            content: '';
            width: 100%;
            height: 0.5rem;
            background: #ddd;
            position: absolute;
            left: -50%;
            top: 1.25rem;
            z-index: -1;
        }

        #progress-bar li:first-child:after {
            content: none;
        }

        #progress-bar li.active:before,
        #progress-bar li.active:after {
            background: #3498db;
            color: white;
            border-color: #3498db;
        }
    </style>

    <div class="ml-64 p-2 flex-1">
        <div class="md:container md:mx-auto mt-2">
            <h2 class="text-center text-2xl font-bold text-sky-700">
                Edit Order Request
            </h2>
<form id="order-form" action="{{ route('order-requests.update', $orderRequest->id) }}" method="POST">
    @csrf
    @method('PATCH')

    {{-- Step 1: Patient Info --}}
    <div class="step bg-sky-300 p-6 rounded-lg mb-6 shadow-lg" id="step-1">
        <h2 class="text-2xl text-center font-bold mb-4 text-gray-600">Patient Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-6">
                <label for="patient_name" class="block text-sm font-medium text-gray-800">Patient Name</label>
                <input type="text" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none @error('patient_name') @enderror" id="patient_name" name="patient_name" value="{{ old('patient_name', $orderRequest->patient_name) }}">
                @error('patient_name')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="patient_id" class="block text-sm font-medium text-gray-800">Patient ID</label>
                <input type="text" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none @error('patient_id') @enderror" id="patient_id" name="patient_id" value="{{ old('patient_id', $orderRequest->patient_id) }}">
                @error('patient_id')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="birthday" class="block text-sm font-medium text-gray-800">Birthday</label>
                <input type="date" class="form-input block w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none @error('birthday') border-blue-500 @enderror" id="birthday" name="birthday" value="{{ old('birthday', $orderRequest->birthday) }}">
                @error('birthday')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6 flex space-x-6">
                <div class="flex-1">
                    <label for="age" class="block text-sm font-medium text-gray-800">Age</label>
                    <input type="text" class="form-input block w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none @error('age') border-red-500 @enderror" id="age" name="age" value="{{ old('age', $orderRequest->age) }}">
                    @error('age')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex-1">
                    <label for="gender" class="block text-sm font-medium text-gray-800">Gender</label>
                    <select class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none @error('gender') @enderror" id="gender" name="gender">
                        <option value="" disabled>Select Gender</option>
                        <option value="Male" {{ old('gender', $orderRequest->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $orderRequest->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $orderRequest->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-6">
                <label for="date_performed" class="block text-sm font-medium text-gray-800">Date Performed</label>
                <input type="date" class="form-input block w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none @error('date_performed') border-red-500 @enderror" id="date_performed" name="date_performed" value="{{ old('date_performed', $orderRequest->date_performed) }}">
                @error('date_performed')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="date_released" class="block text-sm font-medium text-gray-800">Date Released</label>
                <input type="date" class="form-input block w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none @error('date_released') border-red-500 @enderror" id="date_released" name="date_released" value="{{ old('date_released', $orderRequest->date_released) }}">
                @error('date_released')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex justify-end">
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded" onclick="nextStep()">Next</button>
        </div>
    </div>

    {{-- Step 2: Program Selection/Laboratory Services  --}}
    <div class="step hidden bg-sky-300 p-4 rounded-lg mb-4" id="step-2">
        <h2 class="text-xl text-center font-bold mb-4 text-gray-500">Program Selection/Laboratory Services</h2>
        <div class="grid grid-cols-1 gap-4">
            <div class="mb-4 relative">
                <label for="programsDropdown" class="block text-sm font-medium text-gray-700">Programs</label>
                <div class="relative">
                    <button id="programsDropdown" type="button" class="form-multiselect block w-full mt-1 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Select Programs
                        <span id="selectedPrograms" class="ml-2 text-gray-500"></span>
                        <svg class="absolute top-2 right-4 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l5 5a1 1 0 11-1.414 1.414L10 5.414 5.707 9.707a1 1 0 11-1.414-1.414l5-5A1 1 0 0110 3z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div id="programsMenu" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5 focus:outline-none hidden">
                        <div class="py-1">
                            @php
                                // Combine old input and existing data for programs
                                $oldPrograms = old('programs', is_string($orderRequest->programs) ? json_decode($orderRequest->programs, true) : $orderRequest->programs);
                                if (!is_array($oldPrograms)) $oldPrograms = [];
                            @endphp

                            <label class="block px-4 py-2 text-sm text-gray-700">
                                <input type="checkbox" name="programs[]" value="Hematology" class="mr-2" {{ in_array('Hematology', $oldPrograms) ? 'checked' : '' }}> Hematology
                            </label>
                            <label class="block px-4 py-2 text-sm text-gray-700">
                                <input type="checkbox" name="programs[]" value="Clinical Microscopy" class="mr-2" {{ in_array('Clinical Microscopy', $oldPrograms) ? 'checked' : '' }}> Clinical Microscopy
                            </label>
                            <label class="block px-4 py-2 text-sm text-gray-700">
                                <input type="checkbox" name="programs[]" value="Clinical Chemistry" class="mr-2" {{ in_array('Clinical Chemistry', $oldPrograms) ? 'checked' : '' }}> Clinical Chemistry
                            </label>
                            <label class="block px-4 py-2 text-sm text-gray-700">
                                <input type="checkbox" name="programs[]" value="Serology" class="mr-2" {{ in_array('Serology', $oldPrograms) ? 'checked' : '' }}> Serology
                            </label>
                            <label class="block px-4 py-2 text-sm text-gray-700">
                                <input type="checkbox" name="programs[]" value="Electrolytes" class="mr-2" {{ in_array('Electrolytes', $oldPrograms) ? 'checked' : '' }}> Electrolytes
                            </label>
                        </div>
                    </div>
                </div>
                @error('programs')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="order" class="block text-sm font-medium text-gray-700">Order (Panels + Available Test)</label>
                <div id="order-options" class="mt-1 block w-full">
                    {{-- Checkbox options will be populated by JavaScript --}}
                </div>
                @error('order')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex justify-between">
            <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="prevStep()">Previous</button>
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="nextStep()">Next</button>
        </div>
    </div>

    {{-- Step 3: Add Sample  --}}
    <div class="step hidden bg-sky-300 p-6 rounded-lg mb-6 shadow-lg" id="step-3">
        <h2 class="text-2xl text-center font-bold mb-4 text-gray-600">Add Sample</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="mb-6">
                <label for="sample_type" class="block text-sm font-medium text-gray-800">Sample Type</label>
                <input type="text" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none @error('sample_type') @enderror" id="sample_type" name="sample_type" value="{{ old('sample_type', $orderRequest->sample_type) }}">
                @error('sample_type')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="sample_container" class="block text-sm font-medium text-gray-800">Container</label>
                <input type="text" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none @error('sample_container') @enderror" id="sample_container" name="sample_container" value="{{ old('sample_container', $orderRequest->sample_container) }}">
                @error('sample_container')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label for="collection_date" class="block text-sm font-medium text-gray-800">Collection Date</label>
                <input type="date" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none @error('collection_date') @enderror" id="collection_date" name="collection_date" value="{{ old('collection_date', $orderRequest->collection_date) }}">
                @error('collection_date')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="flex justify-between">
            <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="prevStep()">Previous</button>
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="nextStep()">Next</button>
        </div>
    </div>

  {{-- Step 4: Order Form  --}}
<div class="step hidden bg-sky-300 p-6 rounded-lg mb-6 shadow-lg" id="step-4">
    <h2 class="text-2xl text-center font-bold mb-4 text-gray-600">Requisition Form</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Patient Info  --}}
        <div class="mb-6">
            <label for="step4_patient_name" class="block text-sm font-medium text-gray-800">Patient Name</label>
            <input type="text" id="step4_patient_name" name="patient_name" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('patient_name', $orderRequest->patient_name ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="step4_patient_id" class="block text-sm font-medium text-gray-800">Patient ID</label>
            <input type="text" id="step4_patient_id" name="patient_id" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('patient_id', $orderRequest->patient_id ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="step4_birthday" class="block text-sm font-medium text-gray-800">Birthdate</label>
            <input type="date" id="step4_birthday" name="birthday" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('birthday', $orderRequest->birthday ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="step4_age" class="block text-sm font-medium text-gray-800">Age</label>
            <input type="text" id="step4_age" name="age" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('age', $orderRequest->age ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="step4_gender" class="block text-sm font-medium text-gray-800">Gender</label>
            <input type="text" id="step4_gender" name="gender" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('gender', $orderRequest->gender ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="step4_sample_type" class="block text-sm font-medium text-gray-800">Sample Type</label>
            <input type="text" id="step4_sample_type" name="sample_type" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('sample_type', $orderRequest->sample_type ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="step4_sample_container" class="block text-sm font-medium text-gray-800">Sample Container</label>
            <input type="text" id="step4_sample_container" name="sample_container" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('sample_container', $orderRequest->sample_container ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="step4_collection_date" class="block text-sm font-medium text-gray-800">Collection Date</label>
            <input type="date" id="step4_collection_date" name="collection_date" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('collection_date', $orderRequest->collection_date ?? '') }}">
        </div>

        {{-- Service and Test Info --}}
        <div class="mb-6">
            <label for="laboratory_service" class="block text-sm font-medium text-gray-800">Laboratory Service</label>
            <input type="text" id="laboratory_service" name="laboratory_service" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('laboratory_service', implode(', ', $orderRequest->programs ?? [])) }}">
        </div>
        <div class="mb-6">
            <label for="ordered_tests" class="block text-sm font-medium text-gray-800">Ordered Tests</label>
            <input type="text" id="ordered_tests" name="ordered_tests" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('ordered_tests', implode(', ', $orderRequest->order ?? [])) }}">
        </div>
        <div class="mb-6">
            <label for="test_code" class="block text-sm font-medium text-gray-800">Test Code</label>
            <input type="text"  id="test_code" name="test_code" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('test_code', $orderRequest->test_code ?? '') }}">
        </div>

        {{-- Med Tech, Pathologist, and Physician Info --}}
        <div class="mb-6">
            <label for="medtech_full_name" class="block text-sm font-medium text-gray-800">Med Tech Full Name</label>
            <input type="text" id="medtech_full_name" name="medtech_full_name" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('medtech_full_name', $orderRequest->medtech_full_name ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="medtech_lic_no" class="block text-sm font-medium text-gray-800">Med Tech License No.</label>
            <input type="text" id="medtech_lic_no" name="medtech_lic_no" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('medtech_lic_no', $orderRequest->medtech_lic_no ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="pathologist_full_name" class="block text-sm font-medium text-gray-800">Pathologist Full Name</label>
            <input type="text" id="pathologist_full_name" name="pathologist_full_name" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('pathologist_full_name', $orderRequest->pathologist_full_name ?? '') }}">
        </div>
        <div class="mb-6">
            <label for="pathologist_lic_no" class="block text-sm font-medium text-gray-800">Pathologist License No.</label>
            <input type="text" id="pathologist_lic_no" name="pathologist_lic_no" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('pathologist_lic_no', $orderRequest->pathologist_lic_no ?? '') }}">
        </div>

        {{-- Physician Info --}}
        <div class="mb-6">
            <label for="physician_full_name" class="block text-sm font-medium text-gray-800">Physician Full Name</label>
            <input type="text" id="physician_full_name" name="physician_full_name" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" value="{{ old('physician_full_name', $orderRequest->physician_full_name ?? '') }}">
        </div>
    </div>
    <div class="flex justify-between mt-4">
        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="prevStep()">Previous</button>
        <button type="submit" class="bg-green-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Order Request</button>
    </div>
</div>
</form>


<script>
    let currentStep = 1;

    function showStep(step) {
        const steps = document.querySelectorAll('.step');
        steps.forEach((element, index) => {
            element.classList.add('hidden');
            if (index + 1 === step) {
                element.classList.remove('hidden');
            }
        });

        const progressBarItems = document.querySelectorAll('#progress-bar li');
        progressBarItems.forEach((item, index) => {
            if (index + 1 < step) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });

        if (step === 4) {
            prefillStep4();
        }
    }

    function nextStep() {
        console.log("Current Step:", currentStep);
        if (currentStep < 4) {
            saveCurrentStepData();
            currentStep++;
            showStep(currentStep);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function saveCurrentStepData() {
        if (currentStep === 1) {
            localStorage.setItem('patient_name', document.getElementById('patient_name').value);
            localStorage.setItem('birthday', document.getElementById('birthday').value);
            localStorage.setItem('age', document.getElementById('age').value);
            localStorage.setItem('gender', document.getElementById('gender').value);
        } else if (currentStep === 2) {
            const selectedPrograms = Array.from(document.querySelectorAll('#programsMenu input[type="checkbox"]:checked')).map(el => el.value);
            localStorage.setItem('programs', JSON.stringify(selectedPrograms));

            const selectedOrders = Array.from(document.querySelectorAll('#order-options input[type="checkbox"]:checked')).map(order => order.value);
            localStorage.setItem('ordered_tests', JSON.stringify(selectedOrders));
        } else if (currentStep === 3) {
            localStorage.setItem('sample_type', document.getElementById('sample_type').value);
            localStorage.setItem('sample_container', document.getElementById('sample_container').value);
            localStorage.setItem('collection_date', document.getElementById('collection_date').value);
        }
    }

    function prefillStep4() {
        document.getElementById('step4_patient_name').value = localStorage.getItem('patient_name') || '';
        document.getElementById('step4_patient_id').value = localStorage.getItem('patient_id') || '';
        document.getElementById('step4_birthday').value = localStorage.getItem('birthday') || '';
        document.getElementById('step4_age').value = localStorage.getItem('age') || '';
        document.getElementById('step4_gender').value = localStorage.getItem('gender') || '';
        document.getElementById('step4_sample_type').value = localStorage.getItem('sample_type') || '';
        document.getElementById('step4_sample_container').value = localStorage.getItem('sample_container') || '';
        document.getElementById('step4_collection_date').value = localStorage.getItem('collection_date') || '';
        document.getElementById('laboratory_service').value = (JSON.parse(localStorage.getItem('programs')) || []).join(', ');
        document.getElementById('ordered_tests').value = (JSON.parse(localStorage.getItem('ordered_tests')) || []).join(', ');
    }

    function calculateAge() {
        var birthday = new Date(document.getElementById('birthday').value);
        var today = new Date();
        var age = today.getFullYear() - birthday.getFullYear();
        var m = today.getMonth() - birthday.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthday.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    }

    document.getElementById('birthday').addEventListener('change', calculateAge);


    document.addEventListener('DOMContentLoaded', function () {
        const programsDropdown = document.getElementById('programsDropdown');
        const programsMenu = document.getElementById('programsMenu');
        const selectedPrograms = document.getElementById('selectedPrograms');
        const orderOptions = document.getElementById('order-options');
        const testCodeInput = document.getElementById('test_code');
        

        programsDropdown.addEventListener('click', () => {
            programsMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!programsDropdown.contains(event.target) && !programsMenu.contains(event.target)) {
                programsMenu.classList.add('hidden');
            }
        });

        const checkboxes = programsMenu.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                updateSelectedPrograms();
                updateOrderOptions();
            });
        });

        const updateSelectedPrograms = () => {
            const selected = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
            selectedPrograms.textContent = selected.join(', ');
            localStorage.setItem('laboratory_service', selected.join(', '));
        };

        const updateOrderOptions = () => {
            const selected = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            orderOptions.innerHTML = '';

            let options = [];
            if (selected.includes('Hematology')) {
                options = options.concat(['CBC', 'CBC-CT/BT', 'Blood Typing w/ RH', 'Hematology Test 4', 'Hematology Test 5']);
            }
            if (selected.includes('Clinical Microscopy')) {
                options = options.concat(['Fecalysis', 'Pregnancy Tests', 'Urinalysis']);
            }
            if (selected.includes('Clinical Chemistry')) {
                options = options.concat(['Glucose', 'Bilirubin Test', 'Clinical Chemistry', 'Lipid Profile']);
            }
            if (selected.includes('Serology')) {
                options = options.concat(['Dengue Test', 'Serology', 'Thypidot', '2019nCoV']);
            }
            if (selected.includes('Electrolytes')) {
                options = options.concat(['Electrolytes']);
            }

            const oldOrders = {!! json_encode(old('order', $orderRequest->order ?? [])) !!};
            options.forEach(option => {
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'order[]';
                checkbox.value = option;
                checkbox.id = option;
                checkbox.checked = oldOrders.includes(option);

                const label = document.createElement('label');
                label.htmlFor = option;
                label.textContent = option;
                label.classList.add('ml-2');

                const div = document.createElement('div');
                div.classList.add('flex', 'items-center', 'mt-2');
                div.appendChild(checkbox);
                div.appendChild(label);

                orderOptions.appendChild(div);
            });
        };

        
        updateSelectedPrograms();
        updateOrderOptions();
    });

    document.querySelectorAll('.sidebar-dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const menu = this.nextElementSibling;
            if (menu) {
                menu.classList.toggle('hidden');
                const icon = this.querySelector('svg');
                if (icon) {
                    icon.classList.toggle('rotate-90');
                }
            }
        });
    });

    document.querySelector('.sidebar-overlay').addEventListener('click', () => {
        document.querySelector('.sidebar-menu').classList.remove('translate-x-0');
        document.querySelector('.sidebar-menu').classList.add('-translate-x-full');
        document.querySelector('.sidebar-overlay').classList.add('hidden');
    });

    document.querySelector('.sidebar-toggle').addEventListener('click', () => {
        document.querySelector('.sidebar-menu').classList.toggle('-translate-x-full');
        document.querySelector('.sidebar-overlay').classList.toggle('hidden');
    });

    document.addEventListener('DOMContentLoaded', function() {
        showStep(currentStep);
    });
</script>



@endsection

<!DOCTYPE html>
<html lang="en">
@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Worksheet Menu</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .tab-active {
            color: #2563eb; 
            border-bottom: 2px solid #2563eb; 
        }
        .tab-inactive {
            color: #4b5563; 
            border-bottom: 2px solid transparent;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="container mx-auto mt-20 ml-80">
        <div class="bg-white shadow-md rounded p-6 h-screen lg:h-[300vh] max-w-6xl">
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-blue-600">Result Worksheet Menu</h1>
            </div>

            {{-- Sidebar --}}
            <div class="fixed left-0 top-0 w-64 h-full bg-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
                <a href="{{ route('dashboard') }}" class="flex items-center pb-4 border-b border-b-white">
                    <img src="{{ asset('logo/eagles.png') }}" class="h-15 w-20" alt="Eagles Logo">
                    <span class="text-lg font-bold text-white ml-3">Laboratory Information System</span>
                </a>
                
                <ul class="mt-4">
                    <li class="mb-1 group relative">
                        <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                            <i class="ri-home-2-line mr-3 text-lg"></i>
                            <span class="text-sm">Main Dashboard</span>
                        </a>
                    </li>

                    <li class="mb-1 group">
                        <a href="{{ route('order-requests.create') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Add Request</span>
                        </a>
                    </li>
                    <li class="mb-1 group">
                        <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Request Log</span>
                        </a>
                    </li>       
                    <li class="mb-1 group active">
                        <a href="{{ route('results.instrument_import') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Results</span>
                        </a>
                    </li>
                     {{-- Validation --}}
                     <li class="mb-1 group">
                        <a href="#" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                            <i class="ri-flashlight-line mr-3 text-lg"></i>
                            <span class="flex-1 text-sm">Validation</span>
                        </a>
                    </li>
                    <li class="mb-1 group active">
                        <a href="{{ route('patient_log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md">
                            <i class="ri-file-list-line mr-3 text-lg"></i>
                            <span class="text-sm">Patient Log</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
            {{-- end: Sidebar --}}

            <div x-data="{ tab: 'instrument-import' }">
                <ul class="flex space-x-4 border-b mb-4">
                    <li class="pb-2">
                        <a @click.prevent="tab = 'instrument-import'" :class="{ 'tab-active': tab === 'instrument-import', 'tab-inactive': tab !== 'instrument-import' }" href="#" class="inline-block px-4 py-2">Instrument Import</a>
                    </li>
                    <li class="pb-2">
                        <a @click.prevent="tab = 'load-setup-data'" :class="{ 'tab-active': tab === 'load-setup-data', 'tab-inactive': tab !== 'load-setup-data' }" href="#" class="inline-block px-4 py-2">Load Setup Data</a>
                    </li>
                </ul>

                <div x-show="tab === 'instrument-import'">
                    <h2 class="text-xl font-semibold mb-4">Instrument Import</h2>
                    <div class="mb-4">
                        <label for="machine-select" class="block text-sm font-medium text-gray-700">Select Machine</label>
                        <select id="machine-select" name="machine" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">-- Select Machine --</option>
                            <option value="machine_1" {{ $lastMachine === 'machine_1' ? 'selected' : '' }}>Wheisman Hematology Analyzer</option>
                            <option value="machine_2" {{ $lastMachine === 'machine_2' ? 'selected' : '' }}>Sensa Core Electrolyte Analyzer</option>
                            <option value="machine_3" {{ $lastMachine === 'machine_3' ? 'selected' : '' }}>Ema Chem 150 Auto Chemistry Analyzer</option>
                            <option value="machine_4" {{ $lastMachine === 'machine_4' ? 'selected' : '' }}>FICA-I FIA Analyzer</option>
                            <option value="machine_5" {{ $lastMachine === 'machine_5' ? 'selected' : '' }}>Uri-Sed Mini Urine Microscopy Analyzer</option>
                            <option value="machine_6" {{ $lastMachine === 'machine_6' ? 'selected' : '' }}>ICE - ElectroCardioGram</option>
                        </select>
                        <!-- Hidden Machine Input -->
                        <input type="hidden" id="hidden-machine" name="machine" value="">
                    </div>
                </div>

                <div x-show="tab === 'load-setup-data'">
                    <h2 class="text-xl font-semibold mb-4">Load Data Setup</h2>
                    <div class="mb-4">
                        <label for="patient-name" class="block text-sm font-medium text-gray-700">Patient Name</label>
                        <select id="patient-name" name="patient_name" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">-- Select Patient --</option>
                            <!-- Dynamic options will be loaded here -->
                        </select>
                    </div>
                    <div class="flex items-center mb-4">
                        <button id="manual-btn" class="bg-blue-500 text-white px-4 py-2 rounded mr-2" @click="tab = 'manual-input'">Proceed</button>
                    </div>
                </div>

                <div x-show="tab === 'manual-input'">
                    <h2 class="text-xl font-semibold mb-4">Load Data Setup</h2>
                    <div class="mb-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-200 rounded p-2">
                                <p><strong>Name:</strong> <span id="patient-name-display"></span></p>
                                <p><strong>Patient ID:</strong> <span id="patient-id-display"></span></p>
                                <p><strong>Gender:</strong> <span id="patient-gender-display"></span></p>
                                <p><strong>Age:</strong> <span id="patient-age-display"></span></p>
                                <p><strong>Birthday:</strong> <span id="patient-birthday-display"></span></p>
                                <p><strong>Test Program:</strong> <span id="patient-program-display"></span></p>
                                <p><strong>Tests Ordered:</strong> <span id="patient-tests-display"></span></p>
                                <p><strong>Sample Submitted:</strong> <span id="sample_type-display"></span></p>
                                <p><strong>Test Code:</strong> <span id="patient-testcode-display"></span></p>
                                <p><strong>Date Performed:</strong> <span id="patient-date_performed-display"></span></p>
                                <p><strong>Date Released:</strong> <span id="patient-date_released-display"></span></p>
                                <p><strong>Physician:</strong> <span id="physician-display"></span></p>
                            </div>
                            <div class="bg-gray-200 rounded p-2">
                                <label for="test-code" class="block text-sm font-medium text-gray-700">Test Code</label>
                                <input type="text" id="test-code" name="test_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <button id="create-button" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Create</button>
                            </div>
                        </div>
                    </div>

                    <!-- Placeholder for dynamic form -->
                    <form action="{{ route('validate-results') }}" method="POST">
                        @csrf
                        <input type="hidden" name="patient_name" id="hidden-patient-name">
                        <input type="hidden" name="patient_id" id="hidden-patient-id">
                        <input type="hidden" name="gender" id="hidden-gender">
                        <input type="hidden" name="age" id="hidden-age">
                        <input type="hidden" name="birthday" id="hidden-birthday">
                        <input type="hidden" name="test_program" id="hidden-test-program">
                        <input type="hidden" name="tests_ordered" id="hidden-tests-ordered">
                        <input type="hidden" name="sample_type" id="hidden-sample-type">
                        <input type="hidden" name="test_code" id="hidden-test-code">
                        <input type="hidden" name="date_performed" id="hidden-date-performed">
                        <input type="hidden" name="date_released" id="hidden-date-released">
                        <input type="hidden" name="physician_full_name" id="hidden-physician">
                    
                        <!-- New hidden inputs for Med Tech and Pathologist -->
                        <input type="hidden" name="medtech_full_name" id="hidden-medtech-full-name">
                        <input type="hidden" name="medtech_lic_no" id="hidden-medtech-lic-no">
                        <input type="hidden" name="pathologist_full_name" id="hidden-pathologist-full-name">
                        <input type="hidden" name="pathologist_lic_no" id="hidden-pathologist-lic-no">
                        <input type="hidden" name="machine" id="hidden-machine" value="">
                    
                        <div id="dynamic-form" class="mt-4">
                            <!-- Dynamic form content will be loaded here based on Test Code -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/cdn@3.x.x/dist/alpine.js" defer></script>
    <script>
        // Handle the dropdown and sidebar
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.sidebar-dropdown-toggle').forEach(function (dropdown) {
                dropdown.addEventListener('click', function (e) {
                    e.preventDefault();
                    var submenu = dropdown.nextElementSibling;
                    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
                });
            });
        });
    
        // Load patients based on the selected machine
        document.getElementById('machine-select').addEventListener('change', function() {
    const machine = this.value;

    // Save selected machine in the database
    fetch('/store-selected-machine', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ machine: machine })
    }).catch(error => console.error('Error saving machine:', error));

    // Update hidden input field
    document.getElementById('hidden-machine').value = machine;

    // Fetch patients related to the selected machine
    fetch(`/get-patients-by-machine?machine=${machine}`)
        .then(response => response.json())
        .then(data => {
            const patientSelect = document.getElementById('patient-name');
            patientSelect.innerHTML = '<option value="">-- Select Patient --</option>';

            if (data.length === 0) {
                console.warn("No patients found for the selected machine.");
            }

            for (const [id, name] of Object.entries(data)) {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = name;
                patientSelect.appendChild(option);
            }
        })
        .catch(error => console.error('Error fetching patients:', error));
});


    
        // Function to format the date as "Month Day, Year"
        function formatDate(dateStr) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', options);
        }
    
// When patient is selected, populate hidden fields with patient details
document.getElementById('patient-name').addEventListener('change', function() {
    const patientId = this.value;

    fetch(`/get-patient-details?patient_id=${patientId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('patient-name-display').textContent = data.patient_name || '';
                document.getElementById('patient-id-display').textContent = data.patient_id || '';
                document.getElementById('patient-gender-display').textContent = data.gender || '';
                document.getElementById('patient-age-display').textContent = data.age !== null ? data.age : '';
                document.getElementById('patient-birthday-display').textContent = formatDate(data.birthday) || '';
                document.getElementById('patient-program-display').textContent = data.test_program || '';
                document.getElementById('patient-tests-display').textContent = data.tests_ordered || '';
                document.getElementById('sample_type-display').textContent = data.sample_type || '';
                document.getElementById('patient-testcode-display').textContent = data.test_code || '';
                document.getElementById('patient-date_performed-display').textContent = formatDate(data.date_performed) || '';
                document.getElementById('patient-date_released-display').textContent = formatDate(data.date_released) || '';
                document.getElementById('physician-display').textContent = data.physician_full_name || '';

                // Populate hidden inputs for form submission
                document.getElementById('hidden-patient-name').value = data.patient_name || '';
                document.getElementById('hidden-patient-id').value = data.patient_id || '';
                document.getElementById('hidden-gender').value = data.gender || '';
                document.getElementById('hidden-age').value = data.age !== null ? data.age : '';
                document.getElementById('hidden-birthday').value = data.birthday || '';
                document.getElementById('hidden-test-program').value = data.test_program || '';
                document.getElementById('hidden-tests-ordered').value = data.tests_ordered || '';
                document.getElementById('hidden-sample-type').value = data.sample_type || '';
                document.getElementById('hidden-test-code').value = data.test_code || '';
                document.getElementById('hidden-date-performed').value = data.date_performed || '';
                document.getElementById('hidden-date-released').value = data.date_released || '';
                document.getElementById('hidden-physician').value = data.physician_full_name || '';

                // Populate hidden inputs for Med Tech and Pathologist information
                document.getElementById('hidden-medtech-full-name').value = data.medtech_full_name || '';
                document.getElementById('hidden-medtech-lic-no').value = data.medtech_lic_no || '';
                document.getElementById('hidden-pathologist-full-name').value = data.pathologist_full_name || '';
                document.getElementById('hidden-pathologist-lic-no').value = data.pathologist_lic_no || '';
            }
        })
        .catch(error => console.error('Error fetching patient details:', error));
});




// Helper function to format the date
function formatDate(dateString) {
    const date = new Date(dateString);
    return `${date.getMonth() + 1}/${date.getDate()}/${date.getFullYear()}`;
}

    
        // Load the appropriate test form based on the Test Code input
        document.getElementById('create-button').addEventListener('click', function () {
    const testCode = document.getElementById('test-code').value;

    document.getElementById('hidden-test-code').value = testCode;

    fetch(`/get-test-form?test_code=${testCode}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('dynamic-form').innerHTML = html;
        });
});

    
        const dropdownToggle = document.getElementById('dropdown-toggle-validation');
        const dropdownMenu = document.getElementById('dropdown-menu-validation');
        const dropdownArrow = dropdownToggle.querySelector('svg');
    
        dropdownToggle.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });
    </script>
    
    
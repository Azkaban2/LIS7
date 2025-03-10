@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<div class="ml-64 p-2 flex-1">
    <div class="container mx-auto mt-20"> 
        <h1 class="text-3xl font-semibold mb-1 text-center text-blue-600">Order Requests Log</h1>

    
  {{-- sidebar --}}
  <div class="fixed left-0 top-0 w-64 h-full bg-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
    <a href="dashboard" class="flex items-center pb-4 border-b border-b-white">
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
            <a href="{{ route('order-requests.create') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md">
                <i class="ri-instance-line mr-3 text-lg"></i>
                <span class="text-sm">Add Request</span>
            </a>
        </li>        
        <li class="mb-1 group active">
            <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                <i class="ri-instance-line mr-3 text-lg"></i>
                <span class="text-sm">Request Log</span>
            </a>
        </li>
        <li class="mb-1 group">
            <a href="{{ route('results.instrument_import') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                <i class="ri-instance-line mr-3 text-lg"></i>
                <span class="text-sm">Results</span>
            </a>
        </li>
           {{-- Validation--}}
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



<div class="my-4 flex">
    {{-- Search Input --}}
    <div class="mr-4">
        <input type="text" id="searchInput" placeholder="Search..." class="border border-sky-500 px-3 py-2 rounded-md w-60">
    </div>

    {{-- Filter by Program Dropdown --}}
    <div>
        <label for="filterProgram">Filter by Program:</label>
        <select id="filterProgram" class="border border-gray-300 rounded-md px-3 py-2 w-60">
            <option value="">All</option>
            <option value="Hematology">Hematology</option>
            <option value="Clinical Microscopy">Clinical Microscopy</option>
            <option value="Clinical Chemistry">Clinical Chemistry</option>
            <option value="Serology">Serology</option>
            <option value="Electrolytes">Electrolytes</option>
        </select>
    </div>
</div>

{{-- Session Messages --}}
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

{{-- Order Requests Table --}}
@if($orderRequests->isEmpty())
    <p class="text-center text-gray-600 mt-4">No order requests found.</p>
@else
    <div class="overflow-x-auto bg-gray-100 shadow-md rounded-lg overflow-y-auto">
        <table id="orderRequestsTable" class="table-fixed border-collapse border border-red-300 w-full">
            <thead>
                <tr class="bg-sky-300">
                    <th class="px-4 py-2 text-left w-56 border border-gray-300">Patient ID</th>
                    <th class="px-4 py-2 text-left w-48 border border-gray-300">Patient Name</th>
                    <th class="px-4 py-2 text-left w-28 border border-gray-300">Birthday</th>
                    <th class="px-4 py-2 text-left w-16 border border-gray-300">Age</th>
                    <th class="px-4 py-2 text-left w-20 border border-gray-300">Gender</th>
                    <th class="px-4 py-2 text-left w-32 border border-gray-300">Date Performed</th>
                    <th class="px-4 py-2 text-left w-32 border border-gray-300">Date Released</th>
                    <th class="px-4 py-2 text-left w-48 border border-gray-300">Programs</th> 
                    <th class="px-4 py-2 text-left w-32 border border-gray-300">Sample Type</th>
                    <th class="px-4 py-2 text-left w-36 border border-gray-300">Sample Container</th>
                    <th class="px-4 py-2 text-left w-36 border border-gray-300">Collection Date</th>
                    <th class="px-4 py-2 text-left w-48 border border-gray-300">Order</th> 
                    <th class="px-4 py-2 text-left w-36 border border-gray-300">Test Code</th>
                    <th class="px-4 py-2 text-left w-48 border border-gray-300">Medtech Full Name</th>
                    <th class="px-4 py-2 text-left w-32 border border-gray-300">Medtech Lic No.</th>
                    <th class="px-4 py-2 text-left w-48 border border-gray-300">Pathologist Full Name</th>
                    <th class="px-4 py-2 text-left w-32 border border-gray-300">Pathologist Lic No.</th>
                    <th class="px-4 py-2 text-left w-48 border border-gray-300">Physician Full Name</th>
                    <th class="px-4 py-2 text-center w-48 border border-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderRequests as $orderRequest)
                <tr class="border-b border-gray-200 hover:bg-emerald-200">
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->patient_id }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->patient_name }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($orderRequest->birthday)->format('m/d/y') }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->age }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->gender }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($orderRequest->date_performed)->format('m/d/y') }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($orderRequest->date_released)->format('m/d/y') }}</td>
                    <td class="px-4 py-2 border border-gray-300">
                        @if(is_array($orderRequest->programs))
                            {{ implode(', ', $orderRequest->programs) }}
                        @elseif(is_string($orderRequest->programs))
                            {{ implode(', ', json_decode($orderRequest->programs, true) ?? [$orderRequest->programs]) }}
                        @else
                            {{ $orderRequest->programs }}
                        @endif
                    </td>                    
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->sample_type }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->sample_container }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ \Carbon\Carbon::parse($orderRequest->collection_date)->format('m/d/y') }}</td>
                    <td class="px-4 py-2 border border-gray-300">
                        @if(is_array($orderRequest->order))
                            {{ implode(', ', $orderRequest->order) }}
                        @elseif(is_string($orderRequest->order))
                            {{ implode(', ', json_decode($orderRequest->order, true) ?? [$orderRequest->order]) }}
                        @else
                            {{ $orderRequest->order }}
                        @endif
                    </td>                  
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->test_code }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->medtech_full_name }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->medtech_lic_no }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->pathologist_full_name }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->pathologist_lic_no }}</td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->physician_full_name }}</td>
                    <td class="px-4 py-2 border border-gray-300">
                        <div class="flex space-x-2">
                            <a href="{{ route('order-requests.edit', $orderRequest->id) }}" class="text-white bg-gradient-to-r from-sky-300 via-sky-400 to-sky-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-sky-300 dark:focus:ring-sky-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Edit</a>
                            <form action="{{ route('order-requests.destroy', $orderRequest->id) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" onclick="showDeleteModal(this)">
                                    Delete
                                </button>
                            </form>
                        </div>                            
                    </td>
                    <td class="px-4 py-2 border border-gray-300">{{ $orderRequest->container_id }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg w-96 p-4">
            <div class="mb-4">
                <h3 class="text-lg font-bold">Are you sure you want to delete this order request?</h3>
                <p class="text-sm text-gray-500">This action cannot be undone.</p>
            </div>
            <div class="flex justify-end">
                <button id="modal-cancel-btn" class="mr-2 px-4 py-2 bg-gray-200 text-black rounded-lg focus:outline-none hover:bg-gray-300">
                    Cancel
                </button>
                <button id="modal-confirm-btn" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    // Function to show the modal
    function showDeleteModal(button) {
        // Store the form's action in a data attribute for later use
        document.querySelector('#modal-confirm-btn').setAttribute('data-form', button.closest('form').action);
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    // Function to hide the modal
    function hideDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    // Event listeners for the modal buttons
    document.getElementById('modal-cancel-btn').addEventListener('click', hideDeleteModal);

    document.getElementById('modal-confirm-btn').addEventListener('click', function () {
        const formAction = this.getAttribute('data-form');
        const deleteForm = document.createElement('form');
        deleteForm.action = formAction;
        deleteForm.method = 'POST';

        // Add CSRF token and DELETE method input
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        deleteForm.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        deleteForm.appendChild(methodInput);

        document.body.appendChild(deleteForm);
        deleteForm.submit();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterProgram = document.getElementById('filterProgram');
        const table = document.getElementById('orderRequestsTable');
        const rows = table.getElementsByTagName('tr');

        // Event listener for search input
        searchInput.addEventListener('keyup', filterTable);

        // Event listener for filter dropdown
        filterProgram.addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterProgramValue = filterProgram.value.toLowerCase();
            
            console.log('Search Term:', searchTerm);
            console.log('Filter Program Value:', filterProgramValue);

            for (let i = 1; i < rows.length; i++) { // start loop from 1 to skip header row
                const cells = rows[i].getElementsByTagName('td');
                const programCell = cells[7]; // program data is in the 8th column (index 7)
                const programText = programCell.innerText.toLowerCase().trim();
                
                console.log('Row:', i, 'Program Cell:', programText);

                // Check if row matches search term and filter value
                let matchesSearchTerm = false;
                let matchesProgramFilter = false;

                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].innerText.toLowerCase().trim();
                    
                    if (searchTerm === '' || cellText.includes(searchTerm)) {
                        matchesSearchTerm = true;
                    }

                    if (filterProgramValue === '' || programText.includes(filterProgramValue)) {
                        matchesProgramFilter = true;
                    }
                }

                if (matchesSearchTerm && matchesProgramFilter) {
                    rows[i].style.display = ''; // show row
                } else {
                    rows[i].style.display = 'none'; // hide row
                }
            }
        }
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
</script>


@endsection

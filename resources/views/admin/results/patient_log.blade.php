@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<div class="ml-64 p-8 flex-1">
    <div class="container mx-auto mt-20">
        <h1 class="text-3xl font-semibold mb-1 text-center text-blue-600">Patient Log</h1>

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
                    <li class="mb-1 group">
                        <a href="{{ route('admin.activity-log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md">
                            <i class="ri-timer-line mr-3 text-lg"></i>
                            <span class="text-sm">Activity Log</span>
                        </a>
                    </li>
                </li>
                <li class="mb-1">
                    <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-instance-line mr-3 text-lg"></i>
                        <span class="text-sm">Request Log</span>
                    </a>
                </li>
                <li class="mb-1 active">
                    <a href="{{ route('patient_log') }}" class="flex items-center py-2 px-4 text-white bg-red-600 rounded-md">
                        <i class="ri-file-list-line mr-3 text-lg"></i>
                        <span class="text-sm">Patient Log</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
        {{-- end: Sidebar --}}

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

        {{-- Patient Log Table --}}
        @if($patientLogs->isEmpty())
            <p class="text-center text-gray-600 mt-4">No patient logs found.</p>
        @else
            <div class="overflow-x-auto bg-gray-100 shadow-md rounded-lg">
                <table id="patientLogsTable" class="table-fixed border-collapse border border-gray-300 w-full">
                    <thead>
                        <tr class="bg-sky-300">
                            <th class="px-4 py-2 text-left w-1/4 border border-gray-300">Patient Name</th>
                            <th class="px-4 py-2 text-left w-1/6 border border-gray-300">Patient ID</th>
                            <th class="px-4 py-2 text-left w-1/6 border border-gray-300">Date Released</th>
                            <th class="px-4 py-2 text-center w-1/6 border border-gray-300">Download PDF</th>
                            <th class="px-4 py-2 text-center w-1/6 border border-gray-300">Actions</th>
                        </tr>
                    </thead>
                    @foreach($patientLogs as $log)
                    <tr class="border-b border-gray-200 hover:bg-emerald-200">
                        <td class="px-4 py-2 border">{{ $log->orderRequest->patient_name ?? $log->patient_name }}</td>
                        <td class="px-4 py-2 border">{{ $log->orderRequest->patient_id ?? $log->patient_id }}</td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($log->date_released)->format('m/d/y') }}</td>
                        <td class="px-4 py-2 text-center border">
                            <a href="{{ route('download.pdf', $log->id) }}" class="text-blue-500 hover:underline">
                                <i class="fas fa-file-pdf text-red-500 text-3xl"></i>
                            </a>
                            <td class="px-4 py-2 text-center border">
                                <button type="button" onclick="showDeleteModal(this)" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2" data-form="{{ route('delete-result', $log->id) }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>                    
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg w-96 p-4">
            <div class="mb-4">
                <h3 class="text-lg font-bold">Are you sure you want to delete this record?</h3>
                <p class="text-sm text-gray-500">This action cannot be undone.</p>
            </div>
            <div class="flex justify-end">
                <button id="modal-cancel-btn" class="mr-2 px-4 py-2 bg-gray-200 text-black rounded-lg focus:outline-none hover:bg-gray-300">
                    Cancel
                </button>
                <button id="modal-confirm-btn" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function showDeleteModal(button) {
        document.querySelector('#modal-confirm-btn').setAttribute('data-form', button.getAttribute('data-form'));
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    document.getElementById('modal-cancel-btn').addEventListener('click', hideDeleteModal);

    document.getElementById('modal-confirm-btn').addEventListener('click', function () {
        const formAction = this.getAttribute('data-form');
        const deleteForm = document.createElement('form');
        deleteForm.action = formAction;
        deleteForm.method = 'POST';

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
</script>

@endsection

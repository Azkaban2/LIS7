@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<div class="ml-80">
    <div class="container mx-auto mt-20">
        <h1 class="text-3xl font-semibold mb-6 text-center text-blue-600">Activity Logs</h1>

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
                <li class="mb-1 group">
                    <a href="{{ route('admin.activity-log') }}" class="flex items-center py-2 px-4 text-white bg-red-600 rounded-md">
                        <i class="ri-timer-line mr-3 text-lg"></i>
                        <span class="text-sm">Activity Log</span>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-instance-line mr-3 text-lg"></i>
                        <span class="text-sm">Request Log</span>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('patient_log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                        <i class="ri-file-list-line mr-3 text-lg"></i>
                        <span class="text-sm">Patient Log</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
        {{-- end: Sidebar --}}

   {{-- Activity Logs Table --}}
   <div class="bg-white shadow-lg rounded-lg p-6 mr-5">
    <div class="mb-4 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold">Activity Logs Table</h2>
        </div>
        <div class="flex gap-4">
            {{-- Export PDF Button --}}
            <a href="{{ route('admin.exportLogsPdf') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Export PDF
            </a>

            {{-- Clear Logs Button --}}
            <form method="POST" action="{{ route('admin.clearLogs') }}" id="clearLogsForm">
                @csrf
                @method('DELETE')
                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
                        onclick="confirmClearLogs()">
                    Clear Logs
                </button>
            </form>
        </div>
    </div>

    {{-- Logs Table --}}
    <table id="activityLogTable" class="table-auto min-w-full border-collapse border border-gray-200">
        <thead>
            <tr class="bg-sky-500 text-white">
                <th class="px-4 py-2 border border-gray-300">#</th>
                <th class="px-4 py-2 border border-gray-300">Action</th>
                <th class="px-4 py-2 border border-gray-300">User</th>
                <th class="px-4 py-2 border border-gray-300">Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr class="border-b border-gray-200 hover:bg-emerald-200">
                    <td class="px-4 py-2 border text-center">{{ $log->id }}</td>
                    <td class="px-4 py-2 border">{{ $log->action }}</td>
                    <td class="px-4 py-2 border text-blue-600 font-semibold">{{ $log->user->name ?? 'System' }}</td>
                    <td class="px-4 py-2 border text-center">{{ $log->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4 flex justify-center">
        <div class="pagination-container">
            {{ $logs->links('pagination::tailwind') }}
        </div>
    </div>
</div>
</div>
</div>

<!-- Include DataTables and custom scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
$('#activityLogTable').DataTable({
paging: false,         // Disable DataTable's pagination
searching: true,
ordering: true,
info: false,           // Disable DataTable's "Showing entries" info
responsive: true,
dom: 'Bfrtip',
buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
});
});

function confirmClearLogs() {
if (confirm('Are you sure you want to clear all logs? This action cannot be undone.')) {
document.getElementById('clearLogsForm').submit();
}
}
</script>
@endsection
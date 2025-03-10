@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<div class="ml-80">
    <div class="container mx-auto mt-20">
        <h1 class="text-3xl font-semibold mb-6 text-center text-blue-600">Lab Inventory</h1>

   <!-- start: Sidebar -->
   <div class="fixed left-0 top-0 w-64 h-full bg-gradient-to-r from-sky-500 to-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
    <a href="dashboard" class="flex items-center pb-4 border-b border-b-white">
        <img src="{{ asset('logo/eagles.png') }}" class="h-15 w-20" alt="Eagles Logo">
        <span class="text-lg font-bold text-white ml-3">Laboratory Information System</span>
    </a>
    <ul class="mt-4">
        <li class="mb-1 group">
            <a href="{{ route('admin.activity-log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md">
                <i class="ri-home-2-line mr-3 text-lg"></i>
                <span class="text-sm">Main Dashboard</span>
            </a>
        </li>
        <li class="mb-1 group">
            <a href="{{ route('admin.activity-log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md">
                <i class="ri-timer-line mr-3 text-lg"></i>
                <span class="text-sm">Activity Log</span>
            </a>
        </li>

        <li class="mb-1 group">
            <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                <i class="ri-instance-line mr-3 text-lg"></i>
                <span class="text-sm">Request Log</span>
            </a>

        
        <li class="mb-1 group active">
            <a href="{{ route('patient_log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md">
                <i class="ri-file-list-line mr-3 text-lg"></i>
                <span class="text-sm">Patient Log</span>
            </a>
        </li>
        <li class="mb-1 group active">
            <a href="{{ route('inventory.index') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                <i class="ri-box-3-line mr-3 text-lg"></i>
                <span class="text-sm">Inventory</span>
            </a>
        </li>
</ul>
</div>
<div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>


  {{-- Add Equipment Button --}}
  <div class="flex justify-end mb-4">
    <a href="{{ route('inventory.create') }}" class="bg-sky-500 text-white px-4 py-2 rounded-lg hover:bg-sky-600">Add Equipment</a>
</div>

{{-- Inventory Table --}}
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-16 py-3">
                    <span class="sr-only">Image</span>
                </th>
                <th scope="col" class="px-6 py-3">Product</th>
                <th scope="col" class="px-6 py-3">Category</th> 
                <th scope="col" class="px-6 py-3">Qty</th>
                <th scope="col" class="px-6 py-3">Price</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="p-4">
                        @if($item->image)
                            <img src="{{ asset('uploads/inventory/' . $item->image) }}" class="w-16 md:w-32 max-w-full max-h-full object-cover rounded" alt="{{ $item->name }}">
                        @else
                            <span class="text-gray-500">No Image</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        {{ $item->name }}
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white"> 
                        {{ $item->category ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <input type="number" id="quantity_{{ $item->id }}" class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 text-center" value="{{ $item->quantity }}" readonly />
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                        ₱{{ number_format($item->price, 2) }}
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('inventory.edit', $item->id) }}" 
                            class="relative inline-flex items-center justify-center p-0.5 mb-2 mr-2 overflow-hidden text-sm text-blue-600 font-semibold rounded-lg group bg-gradient-to-br from-sky-500 to-sky-400 group-hover:from-sky-600 group-hover:to-sky-500 hover:text-white">
                            <span class="relative py-2 px-5 transition-all ease-in duration-75 bg-white rounded-lg group-hover:bg-opacity-0">
                                Edit
                            </span>
                        </a>                        
                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="relative inline-flex items-center justify-center p-0.5 mb-2 mr-2 overflow-hidden text-sm text-gray-600 font-semibold rounded-lg group bg-gradient-to-br from-red-500 to-red-400 group-hover:from-red-600 group-hover:to-red-500 hover:text-white"
                                onclick="return confirm('Are you sure you want to delete this item?')">
                                <span class="relative py-2 px-5 transition-all ease-in duration-75 bg-white rounded-lg group-hover:bg-opacity-0">
                                    Remove
                                </span>
                            </button>
                        </form>                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
</div>
</div>
@endsection

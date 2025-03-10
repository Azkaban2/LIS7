@extends('layouts.app')

@section('content')
<div class="ml-80">
    <div class="container mx-auto mt-20"> 
        <h3 class="text-lg font-semibold mb-4">{{ isset($inventory) ? 'Edit' : 'Add' }} Equipment</h3>

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
            <li class="mb-1 group">
                <a href="{{ route('admin.activity-log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md">
                    <i class="ri-timer-line mr-3 text-lg"></i>
                    <span class="text-sm">Activity Log</span>
                </a>
            </li>
        </li>
        <li class="mb-1 group active">
            <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                <i class="ri-instance-line mr-3 text-lg"></i>
                <span class="text-sm">Request Log</span>
            </a>
        </li>
        </li>
        <li class="mb-1 group active">
            <a href="{{ route('patient_log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md">
                <i class="ri-file-list-line mr-3 text-lg"></i>
                <span class="text-sm">Patient Log</span>
            </a>
        </li>

        <li class="mb-1">
            <a href="{{ route('inventory.index') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 rounded-md">
                <i class="ri-box-3-line mr-3 text-lg"></i>
                <span class="text-sm">Inventory</span>
            </a>
        </li>
    </ul>
</div>
<div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
{{-- end: Sidebar --}}

<form action="{{ isset($inventory) ? route('inventory.update', $inventory->id) : route('inventory.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($inventory))
        @method('PUT')
    @endif

    <label class="block mb-2">Equipment Name</label>
    <input type="text" name="name" value="{{ old('name', $inventory->name ?? '') }}" class="w-full border px-2 py-1 rounded mb-4">

    <label class="block mb-2">Category</label>
    <input type="text" name="category" value="{{ old('category', $inventory->category ?? '') }}" class="w-full border px-2 py-1 rounded mb-4">

    <label class="block mb-2">Quantity</label>
    <input type="number" name="quantity" value="{{ old('quantity', $inventory->quantity ?? '') }}" class="w-full border px-2 py-1 rounded mb-4">

    <label class="block mb-2">Price</label>
    <input type="number" name="price" step="0.01" value="{{ old('price', $inventory->price ?? '') }}" class="w-full border px-2 py-1 rounded mb-4">

    <label class="block mb-2">Image</label>
    <input type="file" name="image" class="w-full border px-2 py-1 rounded mb-4">

    @if(isset($inventory) && $inventory->image)
        <img src="{{ asset('uploads/inventory/' . $inventory->image) }}" alt="Inventory Image" class="w-32 h-32 object-cover mb-4">
    @endif

    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
</form>

    </div>
</div>
@endsection

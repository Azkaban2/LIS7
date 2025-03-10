@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="flex">
    <!-- Sidebar -->
    <div class="fixed left-0 top-0 w-64 h-full bg-gradient-to-r from-sky-500 to-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
        <a href="dashboard" class="flex items-center pb-4 border-b border-b-white">
            <img src="{{ asset('logo/eagles.png') }}" class="h-15 w-20" alt="Eagles Logo">
            <span class="text-lg font-bold text-white ml-3">Laboratory Information System</span>
        </a>
        <ul class="mt-4">
            <li class="mb-1 group">
                <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md">
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
                <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md">
                    <i class="ri-instance-line mr-3 text-lg"></i>
                    <span class="text-sm">Request Log</span>
                </a>
            </li>
            <li class="mb-1 group">
                <a href="{{ route('patient_log') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md">
                    <i class="ri-file-list-line mr-3 text-lg"></i>
                    <span class="text-sm">Patient Log</span>
                </a>
            </li>
            <li class="mb-1 group active">
                <a href="{{ route('inventory.index') }}" class="flex items-center py-2 px-4 text-white bg-red-600 rounded-md">
                    <i class="ri-box-3-line mr-3 text-lg"></i>
                    <span class="text-sm">Inventory</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex justify-center items-center min-h-screen bg-gray-100">
        <div class="w-full max-w-lg bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-semibold text-blue-700 mb-6 text-center">Edit Equipment</h1>

            <!-- Form Container -->
            <form action="{{ route('inventory.update', $inventory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Equipment Name -->
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700">Equipment Name</label>
                    <input type="text" name="name" value="{{ old('name', $inventory->name) }}" 
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700">Category</label>
                    <input type="text" name="category" value="{{ old('category', $inventory->category) }}" 
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Quantity & Price -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $inventory->quantity) }}" 
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-semibold text-gray-700">Price (₱)</label>
                        <input type="number" name="price" step="0.01" value="{{ old('price', $inventory->price) }}" 
                               class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="mb-4">
                    <label class="block font-semibold text-gray-700">Image</label>
                    <input type="file" name="image" id="imageUpload" class="w-full border border-gray-300 px-4 py-2 rounded-lg" onchange="previewImage(event)">
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Image Preview -->
                <div class="mb-4 flex justify-center">
                    @if($inventory->image)
                        <img id="imagePreview" src="{{ asset('uploads/inventory/' . $inventory->image) }}" 
                             class="w-40 h-40 object-cover border rounded-lg shadow-md">
                    @else
                        <img id="imagePreview" class="hidden w-40 h-40 object-cover border rounded-lg shadow-md">
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex justify-between">
                    <a href="{{ route('inventory.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-all">
                        Update Equipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function(){
            let output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.classList.remove('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection

@extends('layouts.app')

@section('content')
<div class="ml-80">
    <div class="container mx-auto mt-20">
        <h3 class="text-3xl font-semibold text-center text-blue-600 mb-6">
            {{ isset($inventory) ? 'Edit' : 'Add' }} Equipment
        </h3>

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

        {{-- Form Container --}}
        <div class="max-w-3xl mx-auto bg-sky-300 shadow-lg rounded-lg p-6">
            <form action="{{ isset($inventory) ? route('inventory.update', $inventory->id) : route('inventory.store') }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($inventory))
                    @method('PUT')
                @endif

                {{-- Equipment Name --}}
                <div class="mb-4">
                    <label class="block font-semibold">Equipment Name</label>
                    <input type="text" name="name" 
                           value="{{ old('name', $inventory->name ?? '') }}" 
                           class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>

                {{-- Category Dropdown --}}
                <div class="mb-4">
                    <label class="block font-semibold">Category</label>
                    <select name="category" class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200">
                        <option value="">Select Category</option>
                        <option value="Hematology" {{ (old('category', $inventory->category ?? '') == 'Hematology') ? 'selected' : '' }}>Hematology</option>
                        <option value="Clinical Microscopy" {{ (old('category', $inventory->category ?? '') == 'Clinical Microscopy') ? 'selected' : '' }}>Clinical Microscopy</option>
                        <option value="Clinical Chemistry" {{ (old('category', $inventory->category ?? '') == 'Clinical Chemistry') ? 'selected' : '' }}>Clinical Chemistry</option>
                        <option value="Serology" {{ (old('category', $inventory->category ?? '') == 'Serology') ? 'selected' : '' }}>Serology</option>
                        <option value="Electrolytes" {{ (old('category', $inventory->category ?? '') == 'Electrolytes') ? 'selected' : '' }}>Electrolytes</option>
                        <option value="Others" {{ (old('category', $inventory->category ?? '') == 'Others') ? 'selected' : '' }}>Others</option>
                    </select>
                </div>

                {{-- Quantity --}}
                <div class="mb-4">
                    <label class="block font-semibold">Quantity</label>
                    <input type="number" name="quantity" 
                           value="{{ old('quantity', $inventory->quantity ?? '') }}" 
                           class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>

                {{-- Price --}}
                <div class="mb-4">
                    <label class="block font-semibold">Price (₱)</label>
                    <input type="number" name="price" step="0.01" 
                           value="{{ old('price', $inventory->price ?? '') }}" 
                           class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>

                {{-- Image Upload with Preview --}}
                <div class="mb-4">
                    <label class="block font-semibold">Image</label>
                    <input type="file" name="image" id="imageUpload" 
                           class="w-full border px-3 py-2 rounded-lg focus:ring focus:ring-blue-200">
                    
                    {{-- Preview Image --}}
                    <div id="imagePreviewContainer" class="mt-4 hidden">
                        <p class="text-gray-500 text-sm">Preview:</p>
                        <img id="imagePreview" src="" class="w-32 h-32 object-cover rounded-lg shadow-md">
                    </div>

                    {{-- Display Existing Image (If Editing) --}}
                    @if(isset($inventory) && $inventory->image)
                        <div class="mt-4">
                            <p class="text-gray-500 text-sm">Current Image:</p>
                            <img src="{{ asset('uploads/inventory/' . $inventory->image) }}" class="w-32 h-32 object-cover rounded-lg shadow-md">
                        </div>
                    @endif
                </div>

                {{-- Submit Button --}}
                <div class="mt-6">
                    <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg font-semibold hover:bg-green-600 transition">
                        Save Equipment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript for Image Preview --}}
<script>
    document.getElementById('imageUpload').addEventListener('change', function(event) {
        let imagePreviewContainer = document.getElementById('imagePreviewContainer');
        let imagePreview = document.getElementById('imagePreview');
        let file = event.target.files[0];

        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection

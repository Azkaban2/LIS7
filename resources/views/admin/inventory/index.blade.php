@extends('layouts.app')

@section('content')

<div class="ml-80">
    <div class="container mx-auto mt-20">
        <h1 class="text-4xl font-semibold mb-6 text-center text-blue-600">Lab Inventory</h1>

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

   <!-- Add Equipment Button & Filter -->
   <div class="flex justify-between items-center mb-6 mx-6">
    <a href="{{ route('inventory.create') }}" class="bg-sky-500 text-white px-6 py-3 text-lg font-semibold rounded-lg hover:bg-sky-600 shadow-md">
        Add Equipment
    </a>

    <!-- Category Filter -->
    <div>
        <label for="categoryFilter" class="text-gray-700 font-semibold">Filter by Category:</label>
        <select id="categoryFilter" class="border border-gray-400 rounded-lg px-4 py-2 text-lg">
            <option value="">All</option>
            <option value="Hematology">Hematology</option>
            <option value="Clinical Microscopy">Clinical Microscopy</option>
            <option value="Clinical Chemistry">Clinical Chemistry</option>
            <option value="Serology">Serology</option>
            <option value="Electrolytes">Electrolytes</option>
            <option value="Others">Others</option>
        </select>
    </div>
</div>

<!-- Equipment Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mx-6" id="inventoryGrid">
    @foreach($items as $item)
    <div class="border border-gray-300 shadow-md rounded-lg p-6 bg-white inventory-card" data-category="{{ $item->category }}">
        <div class="h-16 flex justify-between items-start mb-2">
            <h3 class="text-base font-semibold text-gray-800 w-2/3 leading-tight truncate">
                {{ $item->name }}
            </h3>
            <span class="text-sm font-semibold text-white bg-sky-500 rounded-lg px-2 py-1 whitespace-nowrap">
                {{ $item->category ?? 'N/A' }}
            </span>
        </div>
        

        <!-- Equipment Image -->
        <div class="flex justify-center my-4">
            @if($item->image)
                <img src="{{ asset('uploads/inventory/' . $item->image) }}" class="w-32 h-32 object-cover rounded-lg shadow-md" alt="{{ $item->name }}">
            @else
                <span class="text-gray-500">No Image</span>
            @endif
        </div>

        <div class="text-center">
            <p class="text-gray-700"><strong>Quantity:</strong> {{ $item->quantity }}</p>
            <p class="text-gray-700"><strong>Price:</strong> ₱{{ number_format($item->price, 2) }}</p>
        </div>

        <!-- Actions -->
        <div class="flex justify-center space-x-3 mt-4">
            <a href="{{ route('inventory.edit', $item->id) }}" class="bg-blue-600 text-white px-4 py-2 text-sm font-semibold rounded-lg hover:bg-blue-700 shadow-md">
                Edit
            </a>
            <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 text-sm font-semibold rounded-lg hover:bg-red-700 shadow-md" onclick="return confirm('Are you sure you want to delete this item?')">
                    Remove
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
<div class="mt-6 mx-6"></div>


<!-- JavaScript for Filtering -->
<script>
document.getElementById("categoryFilter").addEventListener("change", function () {
let selectedCategory = this.value.toLowerCase();
let cards = document.querySelectorAll(".inventory-card");

cards.forEach(card => {
let cardCategory = card.getAttribute("data-category").toLowerCase();
if (selectedCategory === "" || cardCategory.includes(selectedCategory)) {
    card.style.display = "";
} else {
    card.style.display = "none";
}
});
});
</script>

@endsection

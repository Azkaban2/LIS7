<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIS Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-A5TH6bFv2b9E6naf2tGt13oj1PYlNoJXujtWZwqBwv8FVYdO5wGu8Yj3E6bKNZlqjnpCqmsHQk4+K9oXq4Gdzw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


</head>
<body class="text-gray-800 font-inter">
<div class="flex min-h-screen">
    <!-- start: Sidebar -->
    <div class="fixed left-0 top-0 w-64 h-full bg-gradient-to-r from-sky-500 to-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
        <a href="dashboard" class="flex items-center pb-4 border-b border-b-white">
            <img src="{{ asset('logo/eagles.png') }}" class="h-15 w-20" alt="Eagles Logo">
            <span class="text-lg font-bold text-white ml-3">Laboratory Information System</span>
        </a>

        <ul class="mt-4">
            <li class="mb-1 group active">
                <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class="ri-home-2-line mr-3 text-lg"></i>
                    <span class="text-sm">Main Dashboard</span>
                </a>
            </li>

            <li class="mb-1 group relative">
                <a href="{{ route('order-requests.create') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                    <i class="ri-instance-line mr-3 text-lg"></i>
                    <span class="text-sm">Add Request</span>
                </a>
            </li>

            <li class="mb-1 group">
                <a href="{{ route('order-requests.requestlog') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                    <i class="ri-instance-line mr-3 text-lg"></i>
                    <span class="text-sm">Request Log</span>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="#" class="text-gray-300 text-sm flex items-center hover:text-gray-100 before:content-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Active order</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="text-gray-300 text-sm flex items-center hover:text-gray-100 before:content-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Completed order</a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="text-gray-300 text-sm flex items-center hover:text-gray-100 before:content-[''] before:w-1 before:h-1 before:rounded-full before:bg-gray-300 before:mr-3">Canceled order</a>
                    </li>
                </ul>
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

<!-- Main Dashboard Content -->
@include('layouts.app')

<main class="flex-1 p-6 ml-64 mt-16">
    <div class="py-4">
        <div class="max-w-7xl mx-auto px-6 py-13">
            <div class="bg-sky-100 border-b border-gray-200 rounded-lg shadow-md p-10 h-auto">
                <h1 class="text-4xl font-semibold mb-6">Dashboard Overview</h1>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Order Requests Count -->
                    <div class="bg-sky-500 text-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-4xl font-bold">{{ $orderCount ?? 0 }}</h2>
                                <p class="text-lg">Order Requests</p>
                            </div>
                            <i class="ri-file-list-line text-6xl opacity-50"></i>
                        </div>
                        <a href="{{ route('order-requests.requestlog') }}" class="block mt-4 text-white text-sm font-semibold hover:underline">
                            More info <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </div>

                    <!-- Results Count -->
                    <div class="bg-sky-500 text-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-4xl font-bold">{{ $resultCount ?? 0 }}</h2>
                                <p class="text-lg">Results</p>
                            </div>
                            <i class="ri-flask-line text-6xl opacity-50"></i>
                        </div>
                        <a href="{{ route('patient_log') }}" class="block mt-4 text-white text-sm font-semibold hover:underline">
                            More info <i class="ri-arrow-right-s-line"></i>
                        </a>
                    </div>

                    <!-- Program Counts -->
                    @foreach ($programCounts as $program => $count)
                        <div class="bg-sky-500 text-white p-6 rounded-lg shadow-md">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h2 class="text-4xl font-bold">{{ $count }}</h2>
                                    <p class="text-lg">{{ $program }}</p>
                                </div>
                                @php
                                    $icons = [
                                        'Hematology' => 'ri-drop-line',
                                        'Clinical Microscopy' => 'ri-microscope-line',
                                        'Clinical Chemistry' => 'ri-flask-line',
                                        'Serology' => 'ri-virus-line',
                                        'Electrolytes' => 'ri-water-flash-line',
                                    ];
                                    $icon = $icons[$program] ?? 'ri-stack-line';
                                @endphp
                                <i class="{{ $icon }} text-6xl opacity-50"></i>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-8">
                    <!-- Gender Distribution Chart -->
                    <div class="bg-sky-300 shadow-md rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-4 text-center">Gender Distribution</h2>
                        <canvas id="genderChart" style="width: 300px; height: 300px;"></canvas>
                    </div>
                
                    <!-- Age Distribution Chart -->
                    <div class="bg-sky-300 shadow-md rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-4 text-center">Age Distribution</h2>
                        <canvas id="ageChart" style="width: 300px; height: 300px;"></canvas>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</main>



<!-- JavaScript for dropdown -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gender Distribution Chart
    const genderData = @json(array_values($genderCounts->toArray() ?? []));
    const genderLabels = @json(array_keys($genderCounts->toArray() ?? []));

    new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: {
            labels: genderLabels,
            datasets: [{
                data: genderData,
                backgroundColor: ['#FF6384', '#36A2EB']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // Age Distribution Chart
    const ageData = @json(array_values($ageCounts->toArray() ?? []));
    const ageLabels = @json(array_keys($ageCounts->toArray() ?? []));

    new Chart(document.getElementById('ageChart'), {
        type: 'bar',
        data: {
            labels: ageLabels,
            datasets: [{
                label: 'Age Range',
                data: ageData,
                backgroundColor: ['#FF9F40', '#FFCD56', '#4BC0C0', '#9966FF']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: { title: { display: true, text: 'Age Range' } },
                y: { title: { display: true, text: 'Count' }, beginAtZero: true }
            }
        }
    });
</script>

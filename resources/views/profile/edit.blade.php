@extends('layouts.app')

@section('content')
<div class="flex">

            <!-- Start: Sidebar -->
            <div class="fixed left-0 top-0 w-64 h-full bg-gradient-to-r from-sky-500 to-sky-500 p-4 z-50 sidebar-menu transition-transform overflow-y-auto scrollbar">
                <a href="{{ Auth::check() && Auth::user()->usertype === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center pb-4 border-b border-b-white">
                    <img src="{{ asset('logo/eagles.png') }}" class="h-15 w-20" alt="Eagles Logo">
                    <span class="text-lg font-bold text-white ml-3">Laboratory Information System</span>
                </a>

                <ul class="mt-4">
                    <li class="mb-1 group active">
                        <a href="{{ route('profile.edit') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-700 hover:text-gray-100 rounded-md group-[.active]:bg-red-600 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                            <i class="ri-home-2-line mr-3 text-lg"></i>
                            <span class="text-sm">Profile Edit</span>
                        </a>
                    </li>

                    <li class="mb-1 group relative">
                        <a href="{{ Auth::check() && Auth::user()->usertype === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center py-2 px-4 text-white hover:bg-green-600 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100 sidebar-dropdown-toggle">
                            <i class="ri-instance-line mr-3 text-lg"></i>
                            <span class="text-sm">Main Dashboard</span>
                            <i class="ri-arrow-right-s-line ml-auto group-[.selected]:rotate-90"></i>
                        </a>
                    </li>
                </ul>
            </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-10 bg-gray-100 min-h-screen">
        <!-- Success Message -->
        @if (session('status'))
            <div id="success-message" class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-8 text-center w-1/3 max-w-md border-t-4 border-sky-500">
                    <h3 class="text-xl font-bold text-sky-500 mb-2">SUCCESS!</h3>
                    <p class="text-gray-500 mb-4">{{ session('status') }}</p>
                    <button onclick="hideSuccessMessage()" class="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 focus:outline-none">
                        Continue
                    </button>
                </div>
            </div>
        @endif

<!-- Profile Picture Section -->
<div class="flex flex-col items-center space-y-4 mt-6">
    <!-- Title -->
    <h2 class="text-2xl font-bold text-gray-800">Edit Profile</h2>

    <!-- Profile Picture -->
    <div class="relative">
        <img id="profilePreview"
        src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}" 
        class="h-32 w-32 rounded-full"
        alt="Profile Picture">
   
        <input type="file" name="profile_picture" id="profilePictureInput" accept="image/*" class="hidden" onchange="previewProfilePicture(event)">
    </div>

    <!-- Buttons for Photo Upload -->
    <form method="POST" action="{{ route('profile.updatePicture') }}" enctype="multipart/form-data" class="flex flex-col items-center space-y-2">
        @csrf
        <!-- Choose Photo Button -->
        <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 shadow-sm"
                onclick="document.getElementById('profilePictureInput').click();">
            Choose Photo
        </button>

        <!-- Save Picture Button -->
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md text-sm hover:bg-green-600 shadow-sm">
            Save Picture
        </button>
    </form>
</div>


<!-- Profile Information and Password Update Forms -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <!-- Profile Information Form -->
    <div>
        <h3 class="text-xl font-semibold text-gray-800">Profile Information</h3>
        <form method="POST" action="{{ route('profile.update') }}" class="mt-4">
            @csrf
            @method('PATCH')
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="block w-full mt-2 px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="licensed_number" class="block text-sm font-medium text-gray-600">License Number</label>
                    <input id="licensed_number" type="text" name="licensed_number" value="{{ old('licensed_number', $user->licensed_number) }}" required class="block w-full mt-2 px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="block w-full mt-2 px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-lg">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Password Update Form -->
    <div>
        <h3 class="text-xl font-semibold text-gray-800">Change Password</h3>
        <form method="POST" action="{{ route('profile.changePassword') }}" class="mt-4">
            @csrf
            @method('PUT')
            <div class="space-y-6">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-600">Current Password</label>
                    <input id="current_password" type="password" name="current_password" required class="block w-full mt-2 px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-600">New Password</label>
                    <input id="password" type="password" name="password" required class="block w-full mt-2 px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-600">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required class="block w-full mt-2 px-4 py-3 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg">Change Password</button>
            </div>
        </form>
    </div>
</div>


<!-- Delete Account Section -->
<div class="p-6 bg-red-100 border-l-4 border-red-500 rounded-lg mt-10">
    <h3 class="text-lg font-semibold text-gray-800">Delete Account</h3>
    <form id="delete-form" method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
        @csrf
        @method('DELETE')
        <p class="text-sm text-red-600">Deleting your account is irreversible. Proceed with caution.</p>
        <div class="flex justify-end mt-6">
            <button type="button" onclick="showDeleteModal(this)" class="px-5 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow-lg text-sm">Delete Account</button>
        </div>
    </form>
</div>


<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-lg w-96 p-4">
            <div class="mb-4">
                <h3 class="text-lg font-bold">Are you sure you want to delete your account?</h3>
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
function previewProfilePicture(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('profilePreview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

    // Function to hide the success message
    function hideSuccessMessage() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }

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
   
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const target = document.getElementById(toggle.getAttribute('data-target'));
                const isPassword = target.type === 'password';
                target.type = isPassword ? 'text' : 'password';
                toggle.querySelector('i').classList.toggle('fa-eye-slash', isPassword);
            });
        });
    });
</script>

@endsection

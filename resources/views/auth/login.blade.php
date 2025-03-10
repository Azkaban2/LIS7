<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white dark:bg-white-900">
    <div class="flex justify-center h-screen">
        <div class="hidden bg-cover lg:block lg:w-2/3" style="background-image: url('https://i.im.ge/2024/07/22/VWPtiT.dde440f696efecba310343928a458766.jpeg')">
            <div class="flex items-center h-full px-20 bg-gray-900 bg-opacity-40">
                <div>
                    <h2 class="text-4xl font-bold text-white">Eagles PharmaHealth</h2>
                    <p class="max-w-xl mt-3 text-gray-300">We are committed to providing excellence in pharmaceutical care. Our team of highly skilled pharmacists and healthcare professionals work tirelessly</p>
                </div>
            </div>  
        </div>

        <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
            <div class="flex-1">
                <div class="text-center">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <span class="logo p-b-10 flex justify-center items-center">
                        <a href="https://your-link-here.com">
                            <img src="{{ asset('logo/eagles.png') }}" class="h-30 w-30" alt="Eagles Logo">
                        </a>
                    </span>                    
                    
                    <h2 class="text-4xl font-bold text-center text-blue-700 dark:text-blue">Laboratory Information System</h2>
                    <p class="mt-3 text-gray-500 dark:text-gray-300">Log in to your account</p>
                </div>

                <div class="mt-8">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <div class="mt-4">
                            <x-input-label for="email" :value="__('Email/Username')" />
                            <div class="relative">
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-user-md"></i>
                            </span>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <div class="relative">
                                <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />
                                <span class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-green-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                {{ __('Log in') }}
                            </button>
                        </div>

                        @if (Route::has('register'))
                            <div class="mt-4">
                                <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-green-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                    {{ __('Register') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            togglePassword.addEventListener('click', function (e) {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                // Toggle the eye slash icon
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>

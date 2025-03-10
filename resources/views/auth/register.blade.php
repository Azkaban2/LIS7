<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Roboto&display=swap" rel="stylesheet">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white light:bg-gray-900">
    <div class="flex justify-center h-screen">
        <div class="hidden bg-cover lg:block lg:w-2/3" style="background-image: url('https://i.im.ge/2024/06/06/KP0KNr.dde440f696efecba310343928a458766.jpeg')">
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
                    <span class="logo p-b-10 flex justify-center items-center">
                        <a href="https://your-link-here.com">
                            <img src="{{ asset('logo/eagles.png') }}" class="h-28 w-30" alt="Eagles Logo">
                        </a>
                    </span>                    
                    
                    <h2 class="text-3xl font-bold text-center text-slate-700 :text-white">REGISTER ACCOUNT</h2>
                </div>

                <div class="mt-8">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Medical Technologist Name')" />
                            <x-text-input id="name" class="font-montserrat block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                          <!-- Lic No -->
                          <div>
                            <x-input-label for="licensed_number" :value="__('Medical License Number')" />
                            <x-text-input id="licensed_number" class="block mt-1 w-full" type="text" name="licensed_number" :value="old('licensed_number')" required autofocus autocomplete="licensed_number" />
                            <x-input-error :messages="$errors->get('licensed_number')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Medical Technologist Email/Username')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-slate-700 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50 ms-4">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 text-center">
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

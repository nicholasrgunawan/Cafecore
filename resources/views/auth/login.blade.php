@extends('layouts.default')
@section('title', 'Login')
@section('content')

    <body class="bg-gray-900">

        <div class="flex min-h-screen">
            <!-- Left Side (Image Section) -->
            <div class="w-1/2 bg-cover bg-center flex items-center justify-center"
                style="background-image: url('{{ asset('img/login-background.jpg') }}');">
                <img src="{{ asset('img/logo-login.png') }}" alt="logo login" class="h-100">
            </div>


            <!-- Right Side (Login Form) -->
            <div class="w-1/2 flex items-center justify-center bg-gray-100">
                <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-center text-2xl font-semibold text-gray-800">Login</h2>
                    
            @if (session('status'))
            <p class="text-green-500 text-sm text-center">{{ session('status') }}</p>
        @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        <!-- Email Input -->
                        @csrf
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input autofocus required placeholder="Email" name="email" type="email"
                                class="mt-1 w-full p-2 border rounded-lg focus:ring-teal-500">
                            @if ($errors->has('email'))
                                <p class="text-red-500 text-sm">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <!-- Password Input -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input name="password" placeholder="Password" type="password"
                                class="mt-1 w-full p-2 border rounded-lg focus:ring-teal-500">
                            @if ($errors->has('password'))
                                <p class="text-red-500 text-sm">{{ $errors->first('password') }}</p>
                            @endif

                        </div>

                        <!-- Forgot Password & Login Button -->
                        <div class="flex items-center justify-between mt-4">
                            <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                                Login
                            </button>

                            <a href="{{ route('password.request') }}" class="text-sm text-gray-600 hover:underline">
                                Forgot password? <span class="font-bold">Reset</span>
                            </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </body>
@endsection

@extends('layouts.default')
@section('title', 'Forgot Password')
@section('content')

    <!-- Full-page Flexbox Centered Container -->
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-center text-2xl font-semibold text-gray-800">Reset Password</h2>
            <p class="text-center text-gray-500">Please enter your email to reset your password.</p>

            <!-- Check for success message -->
           
                @if (session('status'))
                    <p class="text-green-500 text-sm text-center">{{ session('status') }}</p>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <!-- Email Input -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input autofocus required placeholder="Email" name="email" type="email"
                            class="mt-1 w-full p-2 border rounded-lg focus:ring-teal-500">
                        @if ($errors->has('email'))
                            <p class="text-red-500 text-sm">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg w-full">
                            Send Reset Link
                        </button>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">Back to Login</a>
                    </div>

                </form>

                
        </div>
    </div>

@endsection

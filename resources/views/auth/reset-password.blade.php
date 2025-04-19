@extends('layouts.default')
@section('title', 'Forgot Password')
@section('content')

    <!-- Full-page Flexbox Centered Container -->
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-center text-2xl font-semibold text-gray-800">Reset Password</h2>
            <p class="text-center text-gray-500">Please enter your new password.</p>

            <!-- Check for success message -->

            @if (session('status'))
                <p class="text-green-500 text-sm text-center">{{ session('status') }}</p>
            @endif

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
            
                <!-- Hidden Input for Token -->
                <input type="hidden" name="token" value="{{ request()->route('token') }}">
            
                <!-- Email Input (Ensure it's included) -->
                <input type="hidden" name="email" value="{{ old('email', request()->email) }}">
            
                <!-- Password Input -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                    <input required placeholder="New password" name="password" type="password"
                        class="mt-1 w-full p-2 border rounded-lg focus:ring-teal-500">
            
                    @if ($errors->has('password'))
                        <p class="text-red-500 text-sm">{{ $errors->first('password') }}</p>
                    @endif
                </div>
            
                <!-- Password Confirmation Input -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input required placeholder="Confirm password" name="password_confirmation" type="password"
                        class="mt-1 w-full p-2 border rounded-lg focus:ring-teal-500">
            
                    @if ($errors->has('password_confirmation'))
                        <p class="text-red-500 text-sm">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>
            
                <!-- Submit Button -->
                <div class="mt-4">
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg w-full">
                        Update Password
                    </button>
                </div>
                
            </form>
            


        </div>
    </div>

@endsection

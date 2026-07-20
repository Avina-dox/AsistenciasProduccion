<x-guest-layout>
    <style>
        /* Personalización de los componentes con tu color #6A2C75 */
        
        /* Enfoque de los campos de texto */
        input[type="email"]:focus, 
        input[type="password"]:focus {
            border-color: #6A2C75 !important;
            --tw-ring-color: rgba(106, 44, 117, 0.2) !important;
            outline: 2px solid transparent !important;
            outline-offset: 2px !important;
        }

        /* Checkbox */
        input[type="checkbox"]:checked {
            background-color: #6A2C75 !important;
            border-color: #6A2C75 !important;
        }
        input[type="checkbox"]:focus {
            --tw-ring-color: rgba(106, 44, 117, 0.2) !important;
        }

        /* Enlaces */
        .hover-color-accent:hover {
            color: #6A2C75 !important;
        }
        .focus-ring-accent:focus {
            --tw-ring-color: #6A2C75 !important;
        }

        /* Botón Principal */
        .btn-accent {
            background-color: #6A2C75 !important;
            transition: all 0.2s ease-in-out !important;
        }
        .btn-accent:hover {
            background-color: #53205c !important; /* Tono más oscuro al pasar el cursor */
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(106, 44, 117, 0.2);
        }
        .btn-accent:active {
            transform: translateY(0);
        }
    </style>

    <div class="mb-6 text-center">
        <!-- Logo o icono opcional arriba del título -->
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl text-white shadow-md" style="background-color: #6A2C75;">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Bienvenido</h2>
        <p class="text-sm text-gray-500 mt-1">Ingresa tus datos para acceder</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm transition duration-150" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="font-semibold text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm transition duration-150"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 shadow-sm" name="remember">
                <span class="ms-2 text-sm text-gray-600 select-none">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-500 hover-color-accent rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus-ring-accent" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <x-primary-button class="w-full justify-center py-2.5 rounded-lg text-sm font-semibold tracking-wide btn-accent">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
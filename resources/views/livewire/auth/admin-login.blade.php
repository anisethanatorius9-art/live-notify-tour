<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Admin Login')" :description="__('Enter your administrator credentials')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <!-- Alert: Admin Only -->
        <div class="flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-4">
            <flux:icon.exclamation-circle class="text-blue-600 mt-1"></flux:icon.exclamation-circle>
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">Admin Area</p>
                <p class="text-sm text-blue-800">Authorized Administrators Only</p>
            </div>
        </div>

        <!-- Email/Password Login -->
        <form method="POST" action="{{ route('admin.login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="admin@example.com"
                :error="$errors->first('email')" />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                    :error="$errors->first('password')" />

                @if (Route::has('password.request'))
                <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                    {{ __('Forgot your password?') }}
                </flux:link>
                @endif
            </div>

            <!-- Admin Key (optional for security) -->
            <flux:input
                name="admin_key"
                label="Admin Access Key (optional)"
                type="password"
                placeholder="Enter admin verification key"
                :error="$errors->first('admin_key')" />

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')"></flux:checkbox>

            <div class="flex items-center justify-end gap-4">
                <flux:link :href="route('login')" wire:navigate class="text-sm">
                    {{ __('Regular Login') }}
                </flux:link>
                <flux:button variant="primary" type="submit" class="flex-1">
                    {{ __('Admin Login') }}
                </flux:button>
            </div>
        </form>

        <!-- Divider -->
        <div class="flex items-center gap-3">
            <div class="flex-1 border-t border-zinc-200 dark:border-zinc-700"></div>
            <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('or') }}</span>
            <div class="flex-1 border-t border-zinc-200 dark:border-zinc-700"></div>
        </div>

        <!-- Email OTP Login Option -->
        <flux:link :href="route('admin.login.email')" wire:navigate class="w-full">
            <flux:button variant="outline" type="button" class="w-full">
                <flux:icon.phone class="mr-2 h-5 w-5"></flux:icon.phone>
                {{ __('Login with Email OTP') }}
            </flux:button>
        </flux:link>

        <!-- Admin Registration -->
        <div class="space-y-2 text-center text-sm text-zinc-600 dark:text-zinc-400">
            <div>
                <span>{{ __('Don\'t have an admin account?') }}</span>
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <flux:link :href="route('admin.register')" wire:navigate class="inline-flex items-center justify-center rounded-full border border-blue-600 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50">
                    {{ __('Register as Admin') }}
                </flux:link>
                <flux:link :href="route('login')" wire:navigate class="inline-flex items-center justify-center text-sm font-semibold text-zinc-700 hover:text-zinc-900">
                    {{ __('Regular Login') }}
                </flux:link>
            </div>
        </div>
    </div>
</x-layouts.auth>

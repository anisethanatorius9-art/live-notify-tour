<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Admin Registration')" :description="__('Fill in the details to create an admin account')" />

        <!-- Alert: Admin Only -->
        <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4">
            <flux:icon.exclamation-circle class="text-amber-600 mt-1"></flux:icon.exclamation-circle>
            <div class="flex-1">
                <p class="text-sm font-medium text-amber-900">Admin Registration</p>
                <p class="text-sm text-amber-800">Please provide valid information and your phone number for SMS verification.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.register.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Full Name -->
            <flux:input
                name="name"
                label="Full Name"
                :value="old('name')"
                type="text"
                required
                autofocus
                placeholder="Admin Name"
                :error="$errors->first('name')" />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="admin@livenotifytour.com"
                :error="$errors->first('email')" />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
                :error="$errors->first('password')" />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm Password')"
                viewable
                :error="$errors->first('password_confirmation')" />

            <!-- Organization / Tourism Type -->
            <flux:select
                name="organization"
                label="Organization / Company"
                placeholder="Select organization type"
                :value="old('organization')"
                :error="$errors->first('organization')"
                required>
                @foreach($organizations as $value => $label)
                    <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select
                name="country"
                label="Country"
                placeholder="Select a country"
                :value="old('country')"
                :error="$errors->first('country')"
                required>
                @foreach($countries as $code => $name)
                    <flux:select.option value="{{ $code }}">{{ $name }}</flux:select.option>
                @endforeach
            </flux:select>

            <!-- Phone Number -->
            <flux:input
                name="phone"
                label="Phone Number"
                :value="old('phone')"
                type="tel"
                placeholder="+255..."
                :error="$errors->first('phone')" />

            <hr class="border-zinc-200 dark:border-zinc-700" />

            <!-- Optional Verification Code -->
            <div class="space-y-4">
                <div class="text-center space-y-2">
                    <flux:label>Admin Verification Code (optional)</flux:label>
                    <flux:text size="sm">If you already have the admin registration code, enter it here; otherwise you can leave it blank.</flux:text>
                </div>

                <flux:otp
                    name="admin_code"
                    length="6"
                    :error="$errors->first('admin_code')"
                    class="mx-auto" />

                <div class="flex flex-col gap-2">
                    <flux:button variant="primary" type="submit" class="w-full">
                        Create Account
                    </flux:button>
                </div>
            </div>

            <div class="flex items-center justify-center mt-4">
                <flux:link :href="route('admin.login')" wire:navigate class="text-sm">
                    {{ __('Already registered? Log in') }}
                </flux:link>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Not an administrator?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Regular Registration') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Admin Phone Login')" :description="__('Verify your identity using your phone number and OTP')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <!-- Access Key Generated Notice -->
        @if(session('access_key_generated'))
        <div id="access-key-notice" class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            <p class="font-medium">{{ __('Your Admin Access Key has been generated!') }}</p>
            <p class="mt-1">{{ __('Use it to log in directly next time instead of entering OTP.') }}</p>
            @if(session('generated_access_key'))
            <code class="mt-2 block rounded-lg bg-amber-100 px-4 py-2 font-mono text-sm text-amber-800">{{ session('generated_access_key') }}</code>
            @endif
        </div>
        @endif

        <!-- Alert: Admin Only -->
        <div class="flex items-start gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-4">
            <flux:icon.exclamation-circle class="text-blue-600 mt-1" />
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">Admin Phone Verification</p>
                <p class="text-sm text-blue-800">Enter your phone number to receive a one-time verification code via SMS or WhatsApp.</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Phone Number Section -->
            <div>
                <div class="max-w-lg mx-auto space-y-2 text-center mb-4">
                    <flux:heading size="lg">{{ __('Enter Your Phone Number') }}</flux:heading>
                    <flux:text>{{ __('A verification code will be sent to your phone via SMS or WhatsApp.') }}</flux:text>
                    <flux:text class="text-amber-700">{{ __('Enter the recipient phone number that should receive the OTP. The sender will be the configured WhatsApp sender number if WhatsApp is used.') }}</flux:text>
                </div>

                <form wire:submit.prevent="sendOtp" class="flex flex-col gap-4">
                    <flux:input
                        wire:model="phone"
                        label="Phone Number"
                        type="tel"
                        placeholder="e.g. 712345678"
                        :error="$errors->first('phone')"
                        required />

                    <flux:label>Country</flux:label>
                    <select wire:model="country" required class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select your country</option>
                        <option value="TZ">Tanzania (+255)</option>
                        <option value="KE">Kenya (+254)</option>
                        <option value="UG">Uganda (+256)</option>
                        <option value="RW">Rwanda (+250)</option>
                        <option value="US">United States (+1)</option>
                        <option value="GB">United Kingdom (+44)</option>
                    </select>
                    @error('country')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror

                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Send Verification Code') }}
                    </flux:button>

                    @if($statusMessage)
                    <p class="text-sm text-zinc-600">{{ __('If another admin needs the code, enter their phone number above and click Send Verification Code again.') }}</p>
                    @endif
                </form>
            </div>

            <!-- OTP Verification Section -->
            @if($statusMessage || $step === 'verify')
            <div class="space-y-4">
                @if($statusMessage)
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-900">
                    <p class="font-semibold mb-2">✓ {{ $statusMessage }}</p>
                    <p class="text-sm">{{ __('Sending code to :phone via :channel.', ['phone' => $statusPhone ?? $formattedPhone ?? $phone, 'channel' => $otpChannel]) }}</p>
                    @if(session('admin_phone_login_otp_display'))
                    <p class="text-xs mt-2 p-2 bg-green-100 rounded text-green-800">
                        <strong>Test OTP Code:</strong> {{ session('admin_phone_login_otp_display') }}
                    </p>
                    @endif
                </div>
                @endif

                @if(!$statusMessage)
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    {{ __('Enter your phone number above and click Send Verification Code to receive your OTP on WhatsApp.') }}
                </div>
                @endif

                <form wire:submit.prevent="verifyOtp" class="flex flex-col gap-4">
                    <flux:otp wire:model="code" length="6" label="OTP Code" label:sr-only :error:icon="false" error:class="text-center" class="mx-auto" />

                    <div class="space-y-3">
                        <flux:button variant="primary" type="submit" class="w-full">
                            {{ __('Verify & Login') }}
                        </flux:button>
                        <flux:button type="button" wire:click.prevent="resend" class="w-full border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                            {{ __('Resend Code') }}
                        </flux:button>
                        <flux:button type="button" wire:click.prevent="useDifferentNumber" class="w-full border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                            {{ __('Use a different phone number') }}
                        </flux:button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Prefer email login?') }}</span>
            <flux:link :href="route('admin.login')" wire:navigate>{{ __('Login with Email') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>

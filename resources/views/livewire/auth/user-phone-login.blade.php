<div class="flex flex-col gap-6">
        <x-auth-header :title="__('Phone Login')" :description="__('Verify your identity using your phone number and OTP')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <div class="space-y-6">
            <!-- Phone Number Section -->
            <div>
                <div class="max-w-lg mx-auto space-y-2 text-center mb-4">
                    <flux:heading size="lg">{{ __('Enter Your Phone Number') }}</flux:heading>
                    <flux:text>{{ __('A verification code will be sent to your phone via SMS.') }}</flux:text>
                </div>

                <form wire:submit.prevent="sendOtp" class="flex flex-col gap-4">
                    <flux:input
                        wire:model="phone"
                        label="Phone Number"
                        type="tel"
                        placeholder="e.g. 712345678"
                        :error="$errors->first('phone')"
                        required />

                    <flux:select wire:model="country" label="Country" placeholder="Select your country" required :error="$errors->first('country')">
                        <flux:select.option value="TZ">Tanzania (+255)</flux:select.option>
                        <flux:select.option value="KE">Kenya (+254)</flux:select.option>
                        <flux:select.option value="UG">Uganda (+256)</flux:select.option>
                        <flux:select.option value="RW">Rwanda (+250)</flux:select.option>
                        <flux:select.option value="US">United States (+1)</flux:select.option>
                        <flux:select.option value="GB">United Kingdom (+44)</flux:select.option>
                    </flux:select>

                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Send Verification Code') }}
                    </flux:button>
                </form>
            </div>

            <!-- OTP Verification Section -->
            @if($statusMessage)
            <div class="space-y-4">
                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
                    {{ $statusMessage }}
                </div>

                <form wire:submit.prevent="verifyOtp" class="flex flex-col gap-4">
                    <flux:otp wire:model="code" length="6" label="OTP Code" label:sr-only :error:icon="false" error:class="text-center" class="mx-auto"></flux:otp>

                    <div class="space-y-3">
                        <flux:button variant="primary" type="submit" class="w-full">
                            {{ __('Verify & Login') }}
                        </flux:button>
                        <flux:button type="button" wire:click="resend" class="w-full border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                            {{ __('Resend Code') }}
                        </flux:button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Prefer email login?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Login with Email') }}</flux:link>
        </div>
    </div>

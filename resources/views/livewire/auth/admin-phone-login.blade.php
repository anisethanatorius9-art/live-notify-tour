<div class="flex flex-col gap-6">
        <x-auth-header :title="__('Admin Email OTP Login')" :description="__('Enter your admin email to receive a one-time verification code by email.')" />

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
            <flux:icon.exclamation-circle class="text-blue-600 mt-1"></flux:icon.exclamation-circle>
            <div class="flex-1">
                <p class="text-sm font-medium text-blue-900">{{ __('Admin Email Verification') }}</p>
                <p class="text-sm text-blue-800">{{ __('Enter your admin email address to receive a secure OTP. After verification, a unique access key will be ready for your next login.') }}</p>
            </div>
        </div>

        <div class="space-y-6 mx-auto w-full max-w-lg px-2 sm:px-0">
            <div>
                <div class="max-w-lg mx-auto space-y-3 text-center mb-6 sm:mb-8">
                    <flux:heading size="lg">{{ __('Enter Your Admin Email') }}</flux:heading>
                    <flux:text class="text-base text-zinc-600 dark:text-zinc-300">{{ __('A one-time login code will be emailed to this address.') }}</flux:text>
                    <flux:text class="text-sm text-amber-700">{{ __('Use a valid administrator email. If you do not receive the email, check spam or try resending the code.') }}</flux:text>
                </div>

                @if($step !== 'verify')
                <form wire:submit.prevent="sendOtp" class="flex flex-col gap-4">
                    <flux:input
                        wire:model="email"
                        label="{{ __('Email address') }}"
                        type="email"
                        placeholder="admin@example.com"
                        :error="$errors->first('email')"
                        required></flux:input>

                    <flux:button variant="primary" type="submit" class="w-full py-4 text-base" wire:loading.attr="disabled" wire:target="sendOtp">
                        {{ __('Send Verification Code') }}
                    </flux:button>

                    @if($statusMessage)
                    <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('If another admin needs the code, enter their email above and click Send Verification Code again.') }}</p>
                    @endif
                </form>
                @endif
            </div>

            @if($statusMessage || $step === 'verify')
            <div class="space-y-4" x-data="{}" x-init="if ('{{ $step }}' === 'verify') { setTimeout(() => { const input = $el.querySelector('input[type=text], input[type=tel], input[type=number]'); if (input) { input.focus(); } }, 50); }">
                @if($statusMessage)
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-900">
                    <p class="font-semibold mb-2">✓ {{ $statusMessage }}</p>
                    <p class="text-sm">{{ __('A secure code was sent to :email.', ['email' => $statusEmail ?? $email]) }}</p>
                    @if(session('admin_email_login_otp_display'))
                    <p class="text-xs mt-2 p-2 bg-green-100 rounded text-green-800">
                        <strong>{{ __('Test OTP Code:') }}</strong> {{ session('admin_email_login_otp_display') }}
                    </p>
                    @endif
                </div>
                @endif

                <form wire:submit.prevent="verifyOtp" class="flex flex-col gap-4">
                    <flux:otp wire:model="code" length="6" label="OTP Code" label:sr-only :error:icon="false" error:class="text-center" class="mx-auto"></flux:otp>

                    <div class="space-y-3">
                        <flux:button variant="primary" type="submit" class="w-full py-4 text-base">
                            {{ __('Verify & Login') }}
                        </flux:button>
                        <flux:button type="button" wire:click.prevent="resend" class="w-full border border-zinc-200 bg-white">
                            {{ __('Resend Code') }}
                        </flux:button>
                        <flux:button type="button" wire:click.prevent="useDifferentEmail" class="w-full border border-zinc-200 bg-white">
                            {{ __('Use a different email') }}
                        </flux:button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600">
            <span>{{ __('Prefer password login?') }}</span>
            <flux:link :href="route('admin.login')" wire:navigate>{{ __('Login with password') }}</flux:link>
        </div>
    </div>

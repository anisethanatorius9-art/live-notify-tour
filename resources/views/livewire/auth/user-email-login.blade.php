<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Email Login')" :description="__('Enter your email to receive a one-time verification code.')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <div class="space-y-6">
        <div>
            <div class="max-w-lg mx-auto space-y-2 text-center mb-4">
                <flux:heading size="lg">{{ __('Enter Your Email Address') }}</flux:heading>
                <flux:text>{{ __('A one-time verification code will be sent to your email. Use it to log in to your dashboard.') }}</flux:text>
            </div>

            @if($step !== 'verify')
                <form wire:submit.prevent="sendOtp" class="flex flex-col gap-4">
                    <flux:input
                        wire:model="email"
                        label="{{ __('Email address') }}"
                        type="email"
                        placeholder="email@example.com"
                        :error="$errors->first('email')"
                        required />

                    <flux:button variant="primary" type="submit" class="w-full">
                        {{ __('Send Verification Code') }}
                    </flux:button>
                </form>
            @endif
        </div>

        @if($statusMessage || $step === 'verify')
            <div class="space-y-4">
                @if($statusMessage)
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
                        <p class="font-semibold mb-2">✓ {{ $statusMessage }}</p>
                        <p class="text-sm">{{ __('A secure code was sent to :email.', ['email' => $statusEmail ?? $email]) }}</p>
                        @if(session('user_email_login_otp_display'))
                            <p class="text-xs mt-2 p-2 bg-blue-100 rounded text-blue-800">
                                <strong>{{ __('Test OTP Code:') }}</strong> {{ session('user_email_login_otp_display') }}
                            </p>
                        @endif
                    </div>
                @endif

                <form wire:submit.prevent="verifyOtp" class="flex flex-col gap-4">
                    <flux:otp wire:model="code" length="6" label="OTP Code" label:sr-only :error:icon="false" error:class="text-center" class="mx-auto" />

                    <div class="space-y-3">
                        <flux:button variant="primary" type="submit" class="w-full">
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
        <flux:link :href="route('login')" wire:navigate>{{ __('Login with Email and Password') }}</flux:link>
    </div>
</div>


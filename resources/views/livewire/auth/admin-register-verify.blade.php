<x-layouts.auth>
    <div class="max-w-lg mx-auto">
        <flux:card>
            <form wire:submit.prevent="verify" class="space-y-8">
                <div class="max-w-64 mx-auto space-y-2 text-center">
                    <flux:heading size="lg">Verify your account</flux:heading>
                    <flux:text>Please enter the one-time password sent to your phone via SMS.</flux:text>
                    @if($phone)
                        <p class="text-sm text-zinc-500">Verification SMS sent to <span class="font-semibold">{{ $formattedPhone }}</span> @if($country) ({{ $country }}) @endif</p>
                    @endif
                </div>

                @if ($statusMessage)
                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
                        {{ $statusMessage }}
                    </div>
                @endif

                <flux:otp wire:model="code" length="6" label="OTP Code" label:sr-only :error:icon="false" error:class="text-center" class="mx-auto" />

                <div class="space-y-4">
                    <flux:button variant="primary" type="submit" class="w-full">Verify</flux:button>
                    <flux:button type="button" wire:click="resend" class="w-full">Resend code</flux:button>
                </div>
            </form>
        </flux:card>
    </div>
</x-layouts.auth>

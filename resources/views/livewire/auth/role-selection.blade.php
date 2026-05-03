<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gray-50 dark:bg-gray-900 antialiased">
    <div class="flex min-h-screen items-center justify-center p-6">
        <div class="w-full max-w-2xl space-y-6">
            <div class="text-center">
                <flux:heading size="lg">{{ __('Choose Your Role') }}</flux:heading>
                <flux:subheading>{{ __('Select how you want to use Live and Notify Tourism') }}</flux:subheading>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach($roles as $roleKey => $role)
                <flux:card wire:click="selectRole('{{ $roleKey }}')" :variant="$selectedRole === $roleKey ? 'primary' : 'outline'" class="cursor-pointer transition-all hover:scale-105">
                    <div class="text-center space-y-4">
                        <flux:icon :name="$role['icon']" size="lg" />
                        <div>
                            <flux:heading size="md">{{ $role['name'] }}</flux:heading>
                            <flux:subheading size="sm">{{ $role['description'] }}</flux:subheading>
                        </div>
                        <flux:badge :variant="$selectedRole === $roleKey ? 'primary' : 'secondary'">
                            {{ $selectedRole === $roleKey ? __('Selected') : __('Choose') }}
                        </flux:badge>
                    </div>
                </flux:card>
                @endforeach
            </div>

            @if($selectedRole && isset($roles[$selectedRole]))
            <flux:card variant="primary">
                <flux:heading size="md">{{ $roles[$selectedRole]['name'] }} Features</flux:heading>
                <flux:subheading>{{ $roles[$selectedRole]['description'] }}</flux:subheading>

                <div class="grid grid-cols-1 gap-3 md:grid-cols-2 mt-4">
                    @foreach($roles[$selectedRole]['features'] as $feature)
                    <div class="flex items-start gap-3 p-3 bg-white dark:bg-gray-800 rounded-lg border">
                        <flux:icon name="check-circle" class="text-green-500 mt-0.5" />
                        <p class="text-sm">{{ $feature }}</p>
                    </div>
                    @endforeach
                </div>
            </flux:card>
            @endif

            @error('role')
            <flux:error>{{ $message }}</flux:error>
            @enderror

            <div class="flex justify-center">
                <flux:button wire:click="confirmRole" :disabled="!$selectedRole" :loading="$loadingConfirmRole" class="w-full max-w-xs">
                    {{ __('Continue') }}
                </flux:button>
            </div>

            <div class="text-center">
                <flux:subheading size="sm" class="text-gray-500">{{ __("You can change your role later in the settings") }}</flux:subheading>
            </div>
        </div>
    </div>
</body>

</html>
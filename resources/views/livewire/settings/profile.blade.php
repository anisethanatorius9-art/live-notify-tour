<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $profilePhoto = '';
    public array $avatarOptions = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->profilePhoto = $user->profile_photo ?? '';
        $this->avatarOptions = [
            'https://api.dicebear.com/6.x/adventurer-neutral/svg?seed=Explorer',
            'https://api.dicebear.com/6.x/adventurer-neutral/svg?seed=Safari',
            'https://api.dicebear.com/6.x/adventurer-neutral/svg?seed=Nomad',
            'https://api.dicebear.com/6.x/adventurer-neutral/svg?seed=Ranger',
            'https://api.dicebear.com/6.x/adventurer-neutral/svg?seed=Guide',
        ];
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],

            'profilePhoto' => ['nullable', 'string', 'url', 'max:2048'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['profilePhoto'])) {
            $user->profile_photo = $validated['profilePhoto'];
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function selectAvatar($avatar = ''): void
    {
        $this->profilePhoto = (string) $avatar;
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<x-layouts.app>
    <section class="w-full">
        @include('partials.settings-heading')

        <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
            <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
                <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

                <div>
                    <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                        <div>
                            <flux:text class="mt-4">
                                {{ __('Your email address is unverified.') }}

                                <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </flux:link>
                            </flux:text>

                            @if (session('status') === 'verification-link-sent')
                                <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </flux:text>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-4 dark:bg-zinc-900 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Profile Avatar') }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('Choose a cartoon avatar for your profile and sidebar.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-14 w-14 overflow-hidden rounded-full border border-gray-200 dark:border-zinc-700">
                                <img src="{{ $profilePhoto ?: auth()->user()->profile_photo ?: 'https://fluxui.dev/img/demo/user.png' }}" alt="{{ __('Avatar') }}" class="h-full w-full object-cover" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mt-4">
                        @foreach ($avatarOptions as $avatar)
                            <button
                                type="button"
                                wire:click="selectAvatar('{{ $avatar }}')"
                                wire:key="avatar-{{ $loop->index }}"
                                class="rounded-xl border p-2 transition focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 {{ $profilePhoto === $avatar ? 'border-blue-600 ring-2 ring-blue-200 dark:ring-blue-500' : 'border-gray-200 dark:border-zinc-700' }}"
                            >
                                <img src="{{ $avatar }}" alt="{{ __('Avatar option') }}" class="h-20 w-full rounded-xl object-cover" />
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        {{ __('Selected avatar will appear in the sidebar profile section.') }}
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                            {{ __('Save') }}
                        </flux:button>
                    </div>

                    <x-action-message class="me-3" on="profile-updated">
                        {{ __('Saved.') }}
                    </x-action-message>
                </div>
            </form>

            <livewire:settings.delete-user-form />
        </x-settings.layout>
    </section>
</x-layouts.app>

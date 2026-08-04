<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" dir="rtl">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" :value="__('ناوی بەکارهێنەر')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('وشەی نهێنی')" />

            <x-text-input id="password" class="block mt-1 w-full text-left" type="password" name="password" required
                autocomplete="current-password" dir="ltr" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="mr-2 text-sm text-gray-600 dark:text-gray-400">{{ __('لەبیرت بمێنێت') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                {{ __('چوونەژوورەوە') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

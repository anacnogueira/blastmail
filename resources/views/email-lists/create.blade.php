<x-app-layout>
    <x-slot name="header">
        <x-h2>
            {{ __('E-mail List') }} > {{ __('Create new list') }}
        </x-h2>
    </x-slot>

    <x-card>
        <x-form :action="route('email-list.store')" method="post" enctype="multipart/form-data">

            <div>
                <x-input-label for="title" :value="__('Title')" />
                <x-text-input id="title" class="block mt-1 w-full" name="title" :value="old('title')" autofocus />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="file" :value="__('File List')" />
                <x-text-input id="file" accept=".csv" class="block mt-1 w-full" name="file" :value="old('file')"
                    type="file" />
                <x-input-error :messages="$errors->get('file')" class="mt-2" />
            </div>

            <div class="flex items-center space-x-4">
                <x-secondary-button type="reset">
                    {{ __('Cancel') }}
                </x-secondary-button>
                <x-primary-button type="submit">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </x-form>
    </x-card>

</x-app-layout>

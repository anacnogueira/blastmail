<x-app-layout>
    <x-slot name="header">
        <x-h2>
            {{ __('E-mail List') }}
        </x-h2>
    </x-slot>
    <x-card>
        @forelse ($emailLists as $list)
            Mostrar listas
        @empty
            <div class="flex justify-center">
                <x-link-button :href="route('email-list.create')">
                    {{ 'Register my first list' }}
                </x-link-button>
            </div>
        @endforelse
    </x-card>
</x-app-layout>

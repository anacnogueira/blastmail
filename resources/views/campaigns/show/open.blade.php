<div class="space-y-4">
    <x-form action="{{ route('campaigns.show', ['campaign' => $campaign, 'what' => $what]) }}" get>
        <x-input.text name="search" :placeholder="__('Search')" value="{{ $search }}" />
    </x-form>

    <x-table :headers="[__('Name'), __('# Openings'), __('Email')]">
        <x-slot name="body">
            <tr>
                <x-table.td class="w-1">Jeremias</x-table.td>
                <x-table.td class="w-1">1</x-table.td>
                <x-table.td class="w-1">jeremias@email.com</x-table.td>
        </x-slot>
    </x-table>
</div>

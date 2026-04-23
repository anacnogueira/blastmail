<x-layouts.app>
    <x-slot name="header">
        <x-h2>
            {{ __('Campaigns') }} > {{ __('Add a new campaign') }}
        </x-h2>
    </x-slot>

    <x-card>
        <x-tabs :tabs="[
            __('Setup') => url('campaigns/create'),
            __('Email Body') => url('campaigns/create/template'),
            __('Schedule') => url('campaigns/create/schedule'),
        ]">
            <x-form :action="route('campaigns.store')" method="post">
                @include("campaigns.create.$view")

                <div class="flex items-center space-x-4">
                    <x-button.link secondary :href="route('campaigns.index')">
                        {{ __('Cancel') }}
                    </x-button.link>
                    <x-button type="submit">
                        {{ __('Save') }}
                    </x-button>
                </div>

            </x-form>
        </x-tabs>
    </x-card>

</x-layouts.app>

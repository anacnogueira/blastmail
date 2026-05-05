<x-mail::message>
    {!! $body !!}

    {{ __('Thanks') }},<br>

    {{ config('app.name') }}

    <img src="{{ route('tracking.openings', $email) }}" style="display: none;" />
</x-mail::message>

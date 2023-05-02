<x-mail::message>
# Webhook notify data

This is response from kbank webhook.

{{ $body['id'] }}
{{ $body['amount'] }}
{{ $body['status'] }}

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

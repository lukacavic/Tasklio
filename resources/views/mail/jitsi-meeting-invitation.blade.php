<x-mail::message>
# Pozdrav,

Ovo je poziv za video sastanak.

<x-mail::button :url="$meetingUrl">
Pridruži se sastanku
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

@component('mail::message')
# New Contact Message
##  From: {{ $subscriber->name }}

{{ $message }}

**Contact Options**
- **Email**: {{ $subscriber->email }}
@if($user->phone)
- **Phone**: {{ $subscriber->phone }}
@endif

@endcomponent

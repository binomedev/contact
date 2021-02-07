@component('mail::message')
# New Contact Message
##  From: {{ $user->name }}

{{ $message }}

**Contact Options**
- **Email**: {{ $user->email }}
@if($user->phone)
- **Phone**: {{ $user->phone }}
@endif

@endcomponent

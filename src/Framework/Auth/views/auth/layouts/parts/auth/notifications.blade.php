@if ($flash->has('success'))
    @include('layouts.parts.auth.notifications.success', [
        'messages' => $flash->get('success')
    ])
@endif

@if($flash->has('error'))
@include('layouts.parts.auth.notifications.error', [
    'messages' => $flash->get('error')
])
@endif
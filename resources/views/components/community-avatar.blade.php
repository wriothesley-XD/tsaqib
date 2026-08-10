<!-- resources/views/components/community-avatar.blade.php -->
@props([
    'user' => null,
    'slug' => null,
    'size' => 'md', // xs, sm, md, lg, xl, 2xl
    'alt' => null,
    'class' => '',
])

@php
    $sizeClasses = [
        'xs' => 'w-7 h-7',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-24 h-24',
        '2xl' => 'w-32 h-32',
    ][$size] ?? 'w-10 h-10';

    if ($user) {
        $avatarUrl = $user->getAvatar();
        $avatarAlt = $alt ?? $user->name;
    } else {
        $avatarUrl = asset('images/community-avatar/default.svg');
        $avatarAlt = $alt ?? 'Avatar';
    }
@endphp

<img src="{{ $avatarUrl }}"
     alt="{{ $avatarAlt }}"
     onerror="this.onerror=null;this.src='{{ asset('images/community-avatar/default.svg') }}'"
     class="{{ $sizeClasses }} rounded-full object-cover border-2 border-white shadow-sm hover:scale-105 hover:shadow-md transition-all duration-200 bg-slate-100 shrink-0 {{ $class }}">

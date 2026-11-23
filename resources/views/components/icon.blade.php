@props(['name' => 'arrow-up-right'])
<svg {{ $attributes->merge(['class' => 'icon']) }} width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($name)
    @case('arrow-up-right') <path d="M7 17 17 7M7 7h10v10"/> @break
    @case('arrow-right') <path d="M4 12h16m-6-6 6 6-6 6"/> @break
    @case('download') <path d="M12 3v12m-5-5 5 5 5-5M5 16v5h14v-5"/> @break
    @case('pin') <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/> @break
    @case('mail') <rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/> @break
    @case('phone') <path d="m8 3 3 5-3 3 5 5 3-3 5 3c0 4-3 6-6 5C8 19 5 16 3 9 2 6 4 3 8 3Z"/> @break
    @case('grid') <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/> @break
    @case('briefcase') <rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V3h8v4M3 12c5 4 13 4 18 0m-9 1v4"/> @break
    @case('book') <path d="m2 8 10-5 10 5-10 5L2 8Zm4 2v7c4 3 8 3 12 0v-7m4-2v8"/> @break
    @case('users') <circle cx="9" cy="8" r="4"/><path d="M2 21v-3a7 7 0 0 1 14 0v3M17 4a4 4 0 0 1 0 8m5 9v-3a7 7 0 0 0-3-6"/> @break
    @case('user') <circle cx="12" cy="8" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3"/> @break
    @case('settings') <path d="M12 3v3m0 12v3M3 12h3m12 0h3M6 6l2 2m8 8 2 2M6 18l2-2m8-8 2-2"/><circle cx="12" cy="12" r="4"/> @break
    @case('plus') <path d="M12 5v14M5 12h14"/> @break
    @case('edit') <path d="m15 4 5 5M4 20l5-1L21 7l-5-5L4 14l-1 7Z"/> @break
    @case('trash') <path d="M3 6h18M5 6l1 15h12l1-15M9 6V3h6v3m-5 4v7m4-7v7"/> @break
    @case('close') <path d="m6 6 12 12M6 18 18 6"/> @break
    @case('search') <circle cx="10" cy="10" r="7"/><path d="m15 15 6 6"/> @break
    @case('logout') <path d="M10 4H4v16h6m4-4 4-4-4-4m-6 4h13"/> @break
    @case('check') <path d="m5 12 4 4L19 6"/> @break
    @case('menu') <path d="M4 6h16M4 12h16M4 18h16"/> @break
    @case('globe') <circle cx="12" cy="12" r="9"/><ellipse cx="12" cy="12" rx="4" ry="9"/><path d="M3 12h18"/> @break
    @case('code') <path d="m8 6-6 6 6 6m8-12 6 6-6 6M14 3l-4 18"/> @break
    @case('heart') <path d="M12 21 3 12C-3 4 7-1 12 6 17-1 27 4 21 12Z"/> @break
    @case('lock') <rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4m-4 5v2"/> @break
    @case('eye') <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/> @break
@endswitch
</svg>

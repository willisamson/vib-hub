@props(['name'])
@php
    $paths = [
        // Fachsymbole
        'sine' => '<path d="M2 12c2.2-6.5 4.3-6.5 5 0s2.8 6.5 5 0 2.8-6.5 5 0 2.8 6.5 5 0"/>',
        'random' => '<path d="M2 12l2-4 1.6 8 2-10 1.8 7 1.6-4 1.8 6 1.8-9 1.6 8 1.8-5 1.6 3 2.4-2"/>',
        'shock' => '<path d="M2 18h5c1.2 0 2.2-13 4.5-13S15 18 16 18l1.5-1.2L19 18h3"/>',
        'resonance' => '<path d="M2 19h3c3 0 4-14 7-14s4 14 7 14h3"/><path d="M12 5v14" stroke-dasharray="2 2.5"/>',
        'modal' => '<path d="M3 14c3-4 6-4 9 0s6 4 9 0"/><path d="M3 10c3 4 6 4 9 0s6-4 9 0" opacity=".5"/><circle cx="3" cy="12" r="1"/><circle cx="21" cy="12" r="1"/>',
        'fem' => '<path d="M4 20 12 4l8 16H4Z"/><path d="M8 12h8M6 16l6-12 6 12M12 20v-8"/>',
        'sensor' => '<rect x="7" y="9" width="10" height="9" rx="1.5"/><path d="M12 9V5M9 21h6M10 13.5h4"/><path d="M5 5c1.5-1.5 3-1.5 4.5 0M14.5 5c1.5-1.5 3-1.5 4.5 0"/>',
        'machine' => '<circle cx="12" cy="12" r="3.2"/><path d="M12 3v2.5M12 18.5V21M3 12h2.5M18.5 12H21M5.6 5.6l1.8 1.8M16.6 16.6l1.8 1.8M5.6 18.4l1.8-1.8M16.6 7.4l1.8-1.8"/>',
        'building' => '<path d="M5 21V8l7-4 7 4v13"/><path d="M9 21v-5h6v5M9 11h.01M15 11h.01"/><path d="M2 21h20" stroke-dasharray="2 2"/>',
        'fixture' => '<rect x="3" y="15" width="18" height="5" rx="1"/><path d="M7 15V9h10v6M10 9V5h4v4"/><circle cx="6" cy="17.5" r=".6"/><circle cx="18" cy="17.5" r=".6"/>',
        'academy' => '<path d="m2 9 10-5 10 5-10 5L2 9Z"/><path d="M6 11v5c3 2.5 9 2.5 12 0v-5M22 9v6"/>',
        'shield' => '<path d="M12 3 4.5 6v6c0 4.5 3.2 7.8 7.5 9 4.3-1.2 7.5-4.5 7.5-9V6L12 3Z"/><path d="m8.5 12 2.4 2.4 4.6-4.8"/>',
        // Oberfläche
        'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'check' => '<path d="m5 12.5 4.2 4.2L19 7"/>',
        'search' => '<circle cx="11" cy="11" r="6.5"/><path d="m16 16 4.5 4.5"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
        'phone' => '<path d="M5 4h3.5l1.5 4.5-2.2 1.3a11 11 0 0 0 6.4 6.4l1.3-2.2L20 15.5V19a1.5 1.5 0 0 1-1.6 1.5A16.5 16.5 0 0 1 3.5 5.6 1.5 1.5 0 0 1 5 4Z"/>',
        'calendar' => '<rect x="3.5" y="5" width="17" height="15.5" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/>',
        'pin' => '<path d="M12 21s7-6.2 7-11.5a7 7 0 0 0-14 0C5 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'clock' => '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21c.9-4.2 4-6.5 8-6.5s7.1 2.3 8 6.5"/>',
        'lock' => '<rect x="4.5" y="10.5" width="15" height="10" rx="2"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3M12 14.5v2.5"/>',
        'logout' => '<path d="M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4M10 16l-4-4 4-4M6 12h10"/>',
        'cookie' => '<path d="M20.5 12.5A8.5 8.5 0 1 1 11.5 3.5a3 3 0 0 0 3.5 3.5 3 3 0 0 0 3 3 2.6 2.6 0 0 0 2.5 2.5Z"/><circle cx="9" cy="10" r=".9"/><circle cx="14.5" cy="15" r=".9"/><circle cx="9.5" cy="15.5" r=".9"/>',
        'inbox' => '<path d="M3 13.5 5.5 5h13l2.5 8.5V19a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5.5Z"/><path d="M3 13.5h5l1.5 2.5h5l1.5-2.5h5"/>',
        'users' => '<circle cx="9" cy="8.5" r="3.5"/><path d="M2.5 20c.8-3.6 3.4-5.5 6.5-5.5s5.7 1.9 6.5 5.5M16 5.2a3.4 3.4 0 0 1 0 6.6M18.5 14.8c1.6.8 2.6 2.5 3 5.2"/>',
        'phone-app' => '<rect x="6.5" y="2.5" width="11" height="19" rx="2.2"/><path d="M10.5 18.5h3"/><path d="M9 10.5l1.5-2.5 1.5 4 1.5-3 1.5 1.5"/>',
        'download' => '<path d="M12 4v11M7 10.5l5 5 5-5M5 20h14"/>',
        'x' => '<path d="M6 6l12 12M18 6 6 18"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
    ];
    $svg = $paths[$name] ?? $paths['sine'];
@endphp
<svg {{ $attributes->merge(['class' => 'icon']) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $svg !!}</svg>

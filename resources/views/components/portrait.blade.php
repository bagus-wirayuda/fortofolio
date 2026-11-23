@props(['profile'])
<div {{ $attributes->class(['portrait', 'portrait-source-cv' => !$profile->photo_path]) }}>
    @if($profile->photo_path)
        <img src="{{ Storage::disk('public')->url($profile->photo_path) }}" alt="Foto {{ $profile->name }}" width="600" height="700">
    @else
        <img src="{{ asset('images/portrait.jpg') }}" alt="Foto {{ $profile->name }}, diambil dari CV" width="387" height="390">
    @endif
</div>

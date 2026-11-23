@extends('layouts.app')
@section('title', 'Masuk Admin — Kalfin Portfolio')
@section('body-class', 'login-page')
@section('content')
<main id="main" class="login-layout">
    <div class="login-story"><a class="brand" href="{{ route('home') }}"><span class="brand-mark">k<span>↗</span></span><span>kalfin.</span></a><div><span class="eyebrow">YOUR STORY, YOUR SPACE</span><h1>Karya yang baik<br>layak untuk<br><em>diceritakan.</em></h1><p>Kelola cerita, perjalanan, dan kontribusi Anda<br>dalam satu ruang yang sederhana.</p><span class="login-asterisk" aria-hidden="true">✳</span></div><span class="login-story-foot">PERSONAL PORTFOLIO / CONTENT STUDIO</span></div>
    <div class="login-form-wrap"><a class="login-back" href="{{ route('home') }}">← Kembali ke website</a><div class="login-form-inner"><span class="login-lock"><x-icon name="lock" /></span><span class="eyebrow">SELAMAT DATANG KEMBALI</span><h2>Ruang kendali Anda.</h2><p>Masuk untuk mengelola portofolio.</p>
    @if($errors->any())<div class="alert alert-error" role="alert">{{ $errors->first() }}</div>@endif
    <form action="{{ route('login.store') }}" method="POST" class="form-stack">@csrf
        <label>Alamat email<input name="email" type="email" value="{{ old('email') }}" autocomplete="username" placeholder="nama@domain.com" required autofocus></label>
        <label>Kata sandi<span class="password-field"><input id="login-password" type="password" name="password" autocomplete="current-password" placeholder="Masukkan kata sandi" required><button type="button" class="password-toggle icon-button" data-password-toggle="login-password" aria-label="Tampilkan kata sandi" aria-pressed="false"><x-icon name="eye" /></button></span></label>
        <button class="button button-dark button-full" type="submit">Masuk ke dashboard <x-icon name="arrow-right" /></button>
    </form><p class="login-help"><x-icon name="lock" /> Akses khusus pengelola portofolio.</p></div><small class="login-copyright">© {{ date('Y') }} Kalfin Alloto’dang</small></div>
</main>
@endsection

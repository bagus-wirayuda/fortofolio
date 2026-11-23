<?php

namespace App\Http\Controllers;

use App\Models\PortfolioEntry;
use App\Models\Profile;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PortfolioController extends Controller
{
    public function index(): View
    {
        return view('portfolio', [
            'profile' => Profile::query()->firstOrFail(),
            'entries' => PortfolioEntry::query()->where('is_published', true)->orderBy('sort_order')->orderBy('id')->get()->groupBy('kind'),
        ]);
    }

    public function media(string $path): StreamedResponse
    {
        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->response($path, headers: ['X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'public, max-age=86400']);
    }

    public function download(): StreamedResponse
    {
        $profile = Profile::query()->firstOrFail();
        abort_unless($profile->cv_path && Storage::disk('local')->exists($profile->cv_path), 404);

        return Storage::disk('local')->download($profile->cv_path, 'CV-Kalfin-Allotodang.pdf', ['Content-Type' => 'application/pdf']);
    }
}

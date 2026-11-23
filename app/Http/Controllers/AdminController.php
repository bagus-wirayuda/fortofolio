<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePortfolioEntryRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\PortfolioEntry;
use App\Models\Profile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $section = $request->query('section', 'project');
        abort_unless(in_array($section, [...array_keys(PortfolioEntry::KINDS), 'profile', 'account']), 404);

        return view('admin.index', [
            'profile' => Profile::query()->firstOrFail(),
            'entries' => PortfolioEntry::query()->orderBy('sort_order')->orderBy('id')->get(),
            'section' => $section,
            'kinds' => PortfolioEntry::KINDS,
        ]);
    }

    public function profile(UpdateProfileRequest $request): RedirectResponse
    {
        $profile = Profile::query()->firstOrFail();
        $data = $request->safe()->except(['photo', 'cv']);
        $oldPhoto = $profile->photo_path;
        $oldCv = $profile->cv_path;
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('portraits', 'public');
        }
        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('cvs', 'local');
        }
        $profile->update($data);
        if (isset($data['photo_path']) && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }
        if (isset($data['cv_path']) && $oldCv) {
            Storage::disk('local')->delete($oldCv);
        }

        return to_route('admin.index', ['section' => 'profile'])->with('success', 'Profil berhasil diperbarui.');
    }

    public function store(StorePortfolioEntryRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('portfolio', 'public');
        }
        PortfolioEntry::query()->create($data);

        return to_route('admin.index', ['section' => $data['kind']])->with('success', 'Data baru berhasil ditambahkan.');
    }

    public function update(StorePortfolioEntryRequest $request, PortfolioEntry $entry): RedirectResponse
    {
        $data = $request->safe()->except(['image', 'remove_image']);
        $oldImage = $entry->image_path;
        if ($request->boolean('remove_image')) {
            $data['image_path'] = null;
        }
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('portfolio', 'public');
        }
        $entry->update($data);
        if (array_key_exists('image_path', $data) && $oldImage && $oldImage !== $entry->image_path) {
            Storage::disk('public')->delete($oldImage);
        }

        return to_route('admin.index', ['section' => $data['kind']])->with('success', 'Perubahan berhasil disimpan.');
    }

    public function destroy(PortfolioEntry $entry): RedirectResponse
    {
        $kind = $entry->kind;
        $image = $entry->image_path;
        $entry->delete();
        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return to_route('admin.index', ['section' => $kind])->with('success', 'Data berhasil dihapus.');
    }

    public function password(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()],
        ]);
        $request->user()->update(['password' => $data['password']]);
        $request->session()->regenerate();

        return to_route('admin.index', ['section' => 'account'])->with('success', 'Kata sandi berhasil diperbarui.');
    }
}

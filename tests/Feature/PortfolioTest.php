<?php

namespace Tests\Feature;

use App\Models\PortfolioEntry;
use App\Models\Profile;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PortfolioSeeder::class);
        $this->withoutVite();
    }

    private function administrator(): User
    {
        $user = User::factory()->create();
        $user->forceFill(['is_admin' => true])->save();

        return $user;
    }

    private function entryData(string $kind = 'project'): array
    {
        return [
            'kind' => $kind,
            'title' => 'Inisiatif uji',
            'subtitle' => 'Institusi uji',
            'period' => '2026',
            'category' => 'Teknologi',
            'description' => 'Deskripsi untuk pengujian.',
            'url' => 'https://example.com',
            'sort_order' => 1,
            'is_published' => true,
        ];
    }

    private function png(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('photo.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jS1kAAAAASUVORK5CYII='));
    }

    public function test_home_shows_cv_content_and_excludes_drafts(): void
    {
        PortfolioEntry::query()->create([...$this->entryData(), 'title' => 'Rahasia draf', 'is_published' => false]);
        $this->get('/')->assertOk()->assertSee('Kalfin Alloto')->assertSee('BISOLPIN')->assertSee('Universitas Hasanuddin')->assertDontSee('Rahasia draf');
    }

    public function test_guests_cannot_access_or_mutate_admin_content(): void
    {
        $entry = PortfolioEntry::query()->first();
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->post('/admin/entries', $this->entryData())->assertRedirect('/admin/login');
        $this->put('/admin/entries/'.$entry->id, $this->entryData())->assertRedirect('/admin/login');
        $this->delete('/admin/entries/'.$entry->id)->assertRedirect('/admin/login');
        $this->put('/admin/profile', [])->assertRedirect('/admin/login');
        $this->assertDatabaseHas('portfolio_entries', ['id' => $entry->id]);
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->post('/admin/entries', $this->entryData())->assertForbidden();
        $this->delete('/admin/entries/1')->assertForbidden();
    }

    public function test_admin_can_create_edit_and_delete_every_entry_kind(): void
    {
        $this->actingAs($this->administrator());
        foreach (array_keys(PortfolioEntry::KINDS) as $kind) {
            $this->post('/admin/entries', $this->entryData($kind))->assertRedirect('/admin?section='.$kind)->assertSessionHasNoErrors();
            $entry = PortfolioEntry::query()->where('title', 'Inisiatif uji')->firstOrFail();
            $this->put('/admin/entries/'.$entry->id, [...$this->entryData($kind), 'title' => 'Sudah diperbarui', 'is_published' => false])->assertSessionHasNoErrors();
            $this->assertDatabaseHas('portfolio_entries', ['id' => $entry->id, 'title' => 'Sudah diperbarui', 'is_published' => false]);
            $this->get('/')->assertDontSee('Sudah diperbarui');
            $this->delete('/admin/entries/'.$entry->id)->assertSessionHasNoErrors();
            $this->assertDatabaseMissing('portfolio_entries', ['id' => $entry->id]);
        }
    }

    public function test_admin_sections_render(): void
    {
        $this->actingAs($this->administrator());
        foreach ([...array_keys(PortfolioEntry::KINDS), 'profile', 'account'] as $section) {
            $this->get('/admin?section='.$section)->assertOk();
        }
        $this->get('/admin?section=invalid')->assertNotFound();
    }

    public function test_invalid_entry_and_unsafe_url_are_rejected(): void
    {
        $this->actingAs($this->administrator())->post('/admin/entries', [
            ...$this->entryData(), 'title' => '', 'kind' => 'invalid',
            'url' => 'javascript:alert(1)', 'sort_order' => -1,
        ])->assertSessionHasErrors(['title', 'kind', 'url', 'sort_order']);
    }

    public function test_images_are_stored_served_and_removed_with_entries(): void
    {
        Storage::fake('public');
        $this->actingAs($this->administrator())->post('/admin/entries', [
            ...$this->entryData(), 'image' => $this->png(),
        ])->assertSessionHasNoErrors();
        $entry = PortfolioEntry::query()->where('title', 'Inisiatif uji')->firstOrFail();
        Storage::disk('public')->assertExists($entry->image_path);
        $this->get('/media/'.$entry->image_path)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $path = $entry->image_path;
        $this->delete('/admin/entries/'.$entry->id)->assertRedirect();
        Storage::disk('public')->assertMissing($path);
        $this->get('/media/'.$path)->assertNotFound();
    }

    public function test_replacing_and_removing_an_image_cleans_old_uploads(): void
    {
        Storage::fake('public');
        $this->actingAs($this->administrator())->post('/admin/entries', [...$this->entryData(), 'image' => $this->png()]);
        $entry = PortfolioEntry::query()->where('title', 'Inisiatif uji')->firstOrFail();
        $oldPath = $entry->image_path;
        $this->put('/admin/entries/'.$entry->id, [...$this->entryData(), 'image' => $this->png()])->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($oldPath);
        $entry->refresh();
        Storage::disk('public')->assertExists($entry->image_path);
        $newPath = $entry->image_path;
        $this->put('/admin/entries/'.$entry->id, [...$this->entryData(), 'remove_image' => '1'])->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($newPath);
        $this->assertNull($entry->fresh()->image_path);
    }

    public function test_executable_files_cannot_be_uploaded_as_images(): void
    {
        $this->actingAs($this->administrator())->post('/admin/entries', [
            ...$this->entryData(), 'image' => UploadedFile::fake()->createWithContent('hack.php', '<?php echo 1;'),
        ])->assertSessionHasErrors('image');
        $this->get('/media/portraits/hack.php')->assertNotFound();
        $this->get('/media/cvs/private.pdf')->assertNotFound();
    }

    public function test_profile_and_documents_can_be_updated(): void
    {
        Storage::fake('public');
        Storage::fake('local');
        $profile = Profile::query()->firstOrFail();
        $this->actingAs($this->administrator())->put('/admin/profile', [
            ...$profile->only(['name', 'credentials', 'headline', 'intro', 'bio', 'location', 'email', 'phone', 'website', 'available']),
            'headline' => 'Headline yang baru',
            'photo' => $this->png(),
            'cv' => UploadedFile::fake()->createWithContent('cv.pdf', "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n%%EOF"),
        ])->assertSessionHasNoErrors()->assertRedirect('/admin?section=profile');
        $profile->refresh();
        Storage::disk('public')->assertExists($profile->photo_path);
        Storage::disk('local')->assertExists($profile->cv_path);
        $this->get('/')->assertSee('Headline yang baru');
        $this->get('/cv/download')->assertOk()->assertDownload('CV-Kalfin-Allotodang.pdf');
    }

    public function test_missing_cv_returns_404(): void
    {
        Storage::fake('local');
        $this->get('/cv/download')->assertNotFound();
    }

    public function test_admin_login_and_logout_work(): void
    {
        $user = $this->administrator();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_wrong_password_and_non_admin_login_are_rejected(): void
    {
        $user = User::factory()->create();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $admin = $this->administrator();
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_attempts_are_throttled(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/admin/login', ['email' => 'unknown@example.com', 'password' => 'wrong']);
        }
        $this->post('/admin/login', ['email' => 'unknown@example.com', 'password' => 'wrong'])->assertTooManyRequests();
    }

    public function test_password_change_requires_current_password_and_confirmation(): void
    {
        $user = $this->administrator();
        $this->actingAs($user)->put('/admin/password', [
            'current_password' => 'wrong', 'password' => 'NewPassword12345', 'password_confirmation' => 'different',
        ])->assertSessionHasErrors(['current_password', 'password']);
        $this->put('/admin/password', [
            'current_password' => 'password', 'password' => 'NewPassword12345', 'password_confirmation' => 'NewPassword12345',
        ])->assertSessionHasNoErrors()->assertRedirect('/admin?section=account');
        $this->assertTrue(Hash::check('NewPassword12345', $user->fresh()->password));
    }

    public function test_content_is_html_escaped(): void
    {
        PortfolioEntry::query()->create([...$this->entryData(), 'title' => '<script>alert(1)</script>']);
        $this->get('/')->assertDontSee('<script>alert(1)</script>', false)->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_seeder_is_idempotent_and_preserves_edits(): void
    {
        $count = PortfolioEntry::query()->count();
        Profile::query()->first()->update(['headline' => 'Perubahan saya']);
        PortfolioEntry::query()->first()->update(['title' => 'Portofolio yang telah diedit']);
        $this->seed(PortfolioSeeder::class);
        $this->assertDatabaseCount('portfolio_entries', $count);
        $this->assertDatabaseHas('profiles', ['headline' => 'Perubahan saya']);
        $this->assertDatabaseHas('portfolio_entries', ['title' => 'Portofolio yang telah diedit']);
    }
}

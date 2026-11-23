<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'credentials', 'headline', 'intro', 'bio', 'location', 'email', 'phone', 'website', 'photo_path', 'cv_path', 'available'])]
class Profile extends Model
{
    protected function casts(): array
    {
        return ['available' => 'boolean'];
    }

    public function whatsappUrl(): string
    {
        $phone = preg_replace('/\D/', '', $this->phone);

        return 'https://wa.me/'.(str_starts_with($phone, '0') ? '62'.substr($phone, 1) : $phone);
    }
}

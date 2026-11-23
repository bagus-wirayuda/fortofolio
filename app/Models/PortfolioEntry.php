<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kind', 'title', 'subtitle', 'period', 'category', 'description', 'url', 'image_path', 'sort_order', 'is_published'])]
class PortfolioEntry extends Model
{
    public const KINDS = [
        'project' => 'Portofolio',
        'experience' => 'Pengalaman',
        'education' => 'Pendidikan',
        'organization' => 'Organisasi',
    ];

    protected function casts(): array
    {
        return ['is_published' => 'boolean', 'sort_order' => 'integer'];
    }
}

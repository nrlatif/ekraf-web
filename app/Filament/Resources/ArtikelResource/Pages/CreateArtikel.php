<?php

namespace App\Filament\Resources\ArtikelResource\Pages;

use App\Filament\Resources\ArtikelResource;
use App\Traits\HandlesCloudinaryUploads;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArtikel extends CreateRecord
{
    use HandlesCloudinaryUploads;
    
    protected static string $resource = ArtikelResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->handleCloudinaryUpload(
            $data,
            'thumbnail',
            'cloudinary_id',
            'cloudinary_meta',
            'articles',
            800,
            450
        );
    }
}

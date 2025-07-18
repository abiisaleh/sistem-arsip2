<?php

namespace App\Filament\Resources\DokumenMasukResource\Pages;

use App\Filament\Resources\DokumenMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDokumenMasuk extends CreateRecord
{
    protected static string $resource = DokumenMasukResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}

<?php

namespace App\Filament\Resources\DokumenMasukResource\Pages;

use App\Filament\Resources\DokumenMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenMasuk extends EditRecord
{
    protected static string $resource = DokumenMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

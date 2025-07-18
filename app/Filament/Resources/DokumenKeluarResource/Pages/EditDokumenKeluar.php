<?php

namespace App\Filament\Resources\DokumenKeluarResource\Pages;

use App\Filament\Resources\DokumenKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenKeluar extends EditRecord
{
    protected static string $resource = DokumenKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

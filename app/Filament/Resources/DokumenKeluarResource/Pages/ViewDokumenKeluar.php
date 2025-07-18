<?php

namespace App\Filament\Resources\DokumenKeluarResource\Pages;

use App\Filament\Resources\DokumenKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDokumenKeluar extends ViewRecord
{
    protected static string $resource = DokumenKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

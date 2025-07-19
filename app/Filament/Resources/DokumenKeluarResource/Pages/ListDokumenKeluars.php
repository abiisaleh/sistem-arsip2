<?php

namespace App\Filament\Resources\DokumenKeluarResource\Pages;

use App\Filament\Resources\DokumenKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenKeluars extends ListRecords
{
    protected static string $resource = DokumenKeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\DokumenMasukResource\Pages;

use App\Filament\Resources\DokumenMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenMasuks extends ListRecords
{
    protected static string $resource = DokumenMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

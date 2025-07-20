<?php

namespace App\Filament\Resources\DokumenKeluarResource\Pages;

use App\Filament\Resources\DokumenKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDokumenKeluar extends CreateRecord
{
    protected static string $resource = DokumenKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        
        if (auth()->user()->divisi != null){
            $data['divisi_id'] = auth()->user()->divisi->id;
        }
        
        return $data;
    }
}

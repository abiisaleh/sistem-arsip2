<?php

namespace App\Filament\Resources\SuratKeluarResource\Pages;

use App\Filament\Resources\SuratKeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratKeluar extends CreateRecord
{
    protected static string $resource = SuratKeluarResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        
        if (auth()->user()->divisi != null){
            $data['divisi_id'] = auth()->user()->divisi->id;
        }
        
        return $data;
    }
}

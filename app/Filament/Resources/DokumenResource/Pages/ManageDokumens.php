<?php

namespace App\Filament\Resources\DokumenResource\Pages;

use App\Filament\Resources\DokumenResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDokumens extends ManageRecords
{
    protected static string $resource = DokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Folder')
                ->mutateFormDataUsing(function ($data) {
                    $data['user_id'] = auth()->id();

                    if (auth()->user()->role == 'user')
                        $data['divisi_id'] = auth()->user()->divisi_id;

                    return $data;
                })
                ->createAnother(false)
                ->modalWidth('md')
                ->modalHeading('Buat Folder baru')
        ];
    }
}

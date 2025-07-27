<?php

namespace App\Filament\Resources\DokumenMasukResource\Pages;

use App\Filament\Resources\DokumenMasukResource;
use App\Models\DokumenMasuk;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListDokumenMasuks extends ListRecords
{
    protected static string $resource = DokumenMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        if (auth()->user()->role == 'user')
            return [];
    
        return [
            'dibuat' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('verified_at', null)),
            'diverifikasi' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('verified_at', '!=',null)->where('archive_at',null)),
            'diarsipkan' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('archive_at', '!=',null)),
        ];
        
    }
}

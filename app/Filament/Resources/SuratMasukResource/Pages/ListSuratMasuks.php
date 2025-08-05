<?php

namespace App\Filament\Resources\SuratMasukResource\Pages;

use App\Filament\Resources\SuratMasukResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSuratMasuks extends ListRecords
{
    protected static string $resource = SuratMasukResource::class;

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

<?php

namespace App\Filament\Resources\DibagikanResource\Pages;

use App\Filament\Resources\DibagikanResource;
use App\Models\Divisi;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

class ManageDibagikans extends ManageRecords
{
    protected static string $resource = DibagikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        if (auth()->user()->role == 'admin') {
            $divisi = Divisi::all()->pluck('judul', 'id');

            foreach ($divisi as $id => $judul) {
                $tab[$judul] = Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->where('divisi_id', $id));
            }

            return $tab;
        }

        return [];
    }
}

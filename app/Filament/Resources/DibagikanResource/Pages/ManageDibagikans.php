<?php

namespace App\Filament\Resources\DibagikanResource\Pages;

use App\Filament\Resources\DibagikanResource;
use App\Models\Divisi;
use App\Models\Dokumen;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;
use Shkubu\FilamentWidgetTabs\Components\WidgetTab;
use Shkubu\FilamentWidgetTabs\Concerns\HasWidgetTabs;

class ManageDibagikans extends ManageRecords
{
    use HasWidgetTabs;

    protected static string $resource = DibagikanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getWidgetTabs(): array
    {
        if (auth()->user()->role == 'admin') {
            $divisi = Divisi::all()->pluck('judul', 'id');


            foreach ($divisi as $id => $judul) {
                $dokumenCount = Dokumen::all()->where('divisi_id', $id)->where('is_private', false)->count();
                $tab[$judul] = WidgetTab::make()
                    ->label($judul)
                    ->icon('heroicon-s-folder')
                    ->value($dokumenCount)
                    ->modifyQueryUsing(fn(Builder $query) => $query->where('divisi_id', $id));
            }

            return $tab;
        }

        return [];
    }
}

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
        return [];
    }

    public function getWidgetTabs(): array
    {
        $divisi = Divisi::all()->pluck('judul', 'id');

        foreach ($divisi as $id => $judul) {
            $dokumen =  Dokumen::query()->whereHas('divisi', fn($query) => $query->where('divisi_id', $id));

            if (auth()->user()->role == 'user')
                $dokumenCount = $dokumen->where('is_private', false)->count();
            else
                $dokumenCount = $dokumen->count();

            $tab[$judul] = WidgetTab::make()
                ->label($judul)
                ->icon('heroicon-s-folder')
                ->value($dokumenCount)
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('divisi', fn($query) => $query->where('divisi_id', $id)));
        }

        return $tab;
    }
}

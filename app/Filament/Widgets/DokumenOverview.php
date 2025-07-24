<?php

namespace App\Filament\Widgets;

use App\Models\DokumenKeluar;
use App\Models\DokumenMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class DokumenOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Dokumen', DokumenMasuk::all()->count() + DokumenKeluar::all()->count()),
            Stat::make('Dokumen Masuk', DokumenMasuk::all()->count()),
            Stat::make('Dokumen Keluar', DokumenKeluar::all()->count()),
        ];
    }
}

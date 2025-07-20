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
            Stat::make('Dokumen Masuk', DokumenMasuk::all()->count()),
            Stat::make('Dokumen Keluar','33'),
        ];
    }
}

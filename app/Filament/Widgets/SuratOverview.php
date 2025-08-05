<?php

namespace App\Filament\Widgets;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class SuratOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Surat', SuratMasuk::all()->count() + SuratKeluar::all()->count()),
            Stat::make('Surat Masuk', SuratMasuk::all()->count()),
            Stat::make('Surat Keluar', SuratKeluar::all()->count()),
        ];
    }
}

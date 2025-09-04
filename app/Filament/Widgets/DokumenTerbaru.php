<?php

namespace App\Filament\Widgets;

use App\Models\Dokumen;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DokumenTerbaru extends BaseWidget
{
    protected static ?string $heading = 'Dokumen minggu ini';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Dokumen::query()->where('created_at', '>=', now()->subWeek())
            )
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->sortable()
                    ->icon(fn($record) => $record->icon)
                    ->iconColor(fn($state) => match (pathinfo($state, PATHINFO_EXTENSION)) {
                        'pdf', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'mp3', 'wav', 'flac', 'acc', 'ogg', 'mp4', 'mov', 'avi', 'mkv', 'wmv' => Color::Red,
                        'doc', 'docx' => Color::Blue,
                        'xls', 'xlsx' => Color::Green,
                        'ppt', 'pptx' => Color::Orange,
                        default => Color::Gray,
                    }),
                Tables\Columns\TextColumn::make('kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->sortable()
                    ->label('Diupload')
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('download')
                    ->hiddenLabel()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn(Dokumen $record) => response()->download('storage/' . $record->file_path, $record->file_name)),
            ])
            ->recordAction('download');
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DibagikanResource\Pages;
use App\Filament\Resources\DibagikanResource\RelationManagers;
use App\Models\Dibagikan;
use App\Models\Dokumen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DibagikanResource extends Resource
{
    protected static ?string $model = Dokumen::class;

    protected static ?string $navigationGroup = 'Arsip';

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('is_private', false))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable()
                    ->icon(fn($record) => $record->icon),
                Tables\Columns\TextColumn::make('kategori')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->options(function () {
                        $options = [];
                        $kategori = Dokumen::all()->groupBy('kategori');
                        if (!$kategori->isEmpty())
                            foreach ($kategori as $key => $value) {
                                $options[$key] = $key;
                            }
                        return $options;
                    })
                    ->attribute('kategori')
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('download')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->action(fn(Dokumen $record) => response()->download('storage/' . $record->file_path, $record->file_name)),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn() => auth()->user()->role == 'admin')
                        ->after(function (Dokumen $record) {
                            if (Storage::disk('public')->exists($record->file_path))
                                return Storage::disk('public')->delete($record->file_path);
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => auth()->user()->role == 'admin')
                        ->after(function (Collection $record) {
                            foreach ($record as $dokumen) {
                                if (Storage::disk('public')->exists($dokumen->file_path))
                                    return Storage::disk('public')->delete($dokumen->file_path);
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDibagikans::route('/'),
        ];
    }
}

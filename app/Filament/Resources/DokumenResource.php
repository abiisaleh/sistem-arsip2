<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenResource\Pages;
use App\Filament\Resources\DokumenResource\RelationManagers;
use App\Models\Divisi;
use App\Models\Dokumen;
use App\Models\kategori;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DokumenResource extends Resource
{
    protected static ?string $model = kategori::class;

    protected static ?string $navigationGroup = 'Drive';

    protected static ?string $label = 'Dokumen Saya';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('divisi_id')
                    ->label('Divisi')
                    ->options(fn() => Divisi::all()->pluck('judul', 'id')->toArray())
                    ->hidden(fn() => auth()->user()->role == 'user')
                    ->hiddenOn('view')
                    ->native(false)
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama folder')
                    ->hiddenOn('view')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('user_id', auth()->id()))
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Folder')
                    ->icon('heroicon-c-folder')
                    ->iconColor('primary'),
                Tables\Columns\TextColumn::make('divisi.judul')
                    ->hidden(fn() => auth()->user()->role == 'user'),
                Tables\Columns\TextColumn::make('dokumens_count')
                    ->label('Total file')
                    ->counts('dokumens'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('primary'),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->modalWidth('sm'),
                    Tables\Actions\DeleteAction::make()
                        ->after(function (Dokumen $record) {
                            if (Storage::disk('public')->exists($record->file_path))
                                return Storage::disk('public')->delete($record->file_path);
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
            'index' => Pages\ManageDokumens::route('/'),
            'view' => Pages\ListFiles::route('/{record}/files'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DokumenRelationManager::class,
        ];
    }
}

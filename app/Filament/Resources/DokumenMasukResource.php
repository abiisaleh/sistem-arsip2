<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenMasukResource\Pages;
use App\Filament\Resources\DokumenMasukResource\RelationManagers;
use App\Models\DokumenMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DokumenMasukResource extends Resource
{
    protected static ?string $model = DokumenMasuk::class;

    protected static ?string $navigationGroup = 'Arsip';

    protected static ?string $pluralLabel = 'Dokumen Masuk';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $recordTitleAttribute = 'nomor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal')
                    ->required(),
                Forms\Components\Select::make('departemen_id')
                    ->native(false)
                    ->relationship('departemen','judul')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('judul'),
                    ])
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('deskripsi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file')
                    ->downloadable()
                    ->openable()
                    ->acceptedFileTypes(['application/pdf'])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('departemen.judul')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenMasuks::route('/'),
            'create' => Pages\CreateDokumenMasuk::route('/create'),
            'view' => Pages\ViewDokumenMasuk::route('/{record}'),
            'edit' => Pages\EditDokumenMasuk::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nomor','judul','deskripsi','departemen.judul'];
    }
}

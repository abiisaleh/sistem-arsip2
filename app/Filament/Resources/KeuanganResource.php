<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeuanganResource\Pages;
use App\Filament\Resources\KeuanganResource\RelationManagers;
use App\Models\Keuangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;

class KeuanganResource extends Resource
{
    protected static ?string $model = Keuangan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('idDokumen')
                ->label('Nomor Dokumen')
                ->required(),
                Forms\Components\TextInput::make('judul')
                ->label('Nama Dokumen'),
                  Forms\Components\Select::make('kategori')
                ->options([
                    'KSBU' => 'KSBU',
                    'KTOKPD' => 'KTOKPD',
                    'KASI JASA' => 'KASI JASA',
                    'BLU' => 'BLU',
                    'SPI' => 'SPI',
                    'PENGELOLA ANGGARAN' => 'PENGELOLA ANGGARAN',
                ]),
                
                Forms\Components\DatePicker::make('tanggal')
                ->maxDate(now()),
                Forms\Components\TextInput::make('deskripsi'),
                Forms\Components\TextInput::make('Oleh')
                ->label('Diupload Oleh'), //terhubung langsung sama siapa yang login

                FileUpload::make('file')
                ->label('Upload File Arsip'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('idDokumen')
                ->searchable()
                ->label('No.Dokumen'),
                Tables\Columns\TextColumn::make('judul')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('kategori'),
                Tables\Columns\TextColumn::make('tanggal')
                ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi'),
                Tables\Columns\TextColumn::make('Oleh'),
                Tables\Columns\TextColumn::make('file'),
            ])
            ->filters([
                 Tables\Filters\SelectFilter::make('kategori')
               ->options([
                    'KSBU' => 'KSBU',
                    'KTOKPD' => 'KTOKPD',
                    'KASI JASA' => 'KASI JASA',
                    'BLU' => 'BLU',
                    'SPI' => 'SPI',
                    'PENGELOLA ANGGARAN' => 'PENGELOLA ANGGARAN',
            ])
            ])
            ->actions([
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
            'index' => Pages\ListKeuangans::route('/'),
            'create' => Pages\CreateKeuangan::route('/create'),
            'edit' => Pages\EditKeuangan::route('/{record}/edit'),
        ];
    }
}

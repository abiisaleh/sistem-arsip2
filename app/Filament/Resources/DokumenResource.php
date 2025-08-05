<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DokumenResource\Pages;
use App\Filament\Resources\DokumenResource\RelationManagers;
use App\Models\Divisi;
use App\Models\Dokumen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DokumenResource extends Resource
{
    protected static ?string $model = Dokumen::class;

    protected static ?string $navigationGroup = 'Arsip';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('divisi_id')
                    ->native(false)
                    ->relationship('divisi', 'judul')
                    ->visible(fn() => auth()->user()->role == 'admin')
                    ->live(),
                Forms\Components\Select::make('kategori')
                    ->native(false)
                    ->disabled(fn(Get $get) => (auth()->user()->role == 'admin') & is_null($get('divisi_id')))
                    ->options(function (Get $get) {
                        $data = null;

                        if (auth()->user()->role == 'user')
                            $data = auth()->user()->divisi->kategori;

                        if (auth()->user()->role == 'admin')
                            if (!is_null($get('divisi_id')))
                                $data = Divisi::query()->find($get('divisi_id'))->toArray()['kategori'];

                        if (!is_null($data))
                            return collect($data)->mapWithKeys(fn($item, $key) => [$item => $item])->all();

                        return [];
                    }),
                Forms\Components\Toggle::make('is_private'),
                Forms\Components\FileUpload::make('file_path')
                    ->previewable(false)
                    ->storeFileNamesIn('file_name')
                    ->directory(function (Get $get) {
                        $divisi = (auth()->user()->role == 'admin') ? Divisi::find($get('divisi_id'))->judul : auth()->user()->divisi->judul;
                        return now()->year . '/' . $divisi . '/' . $get('kategori');
                    })
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->icon(fn($record) => $record->icon),
                Tables\Columns\TextColumn::make('divisi.judul')
                    ->visible(auth()->user()->role == 'admin'),
                Tables\Columns\TextColumn::make('kategori')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_private')
                    ->label('Hidden')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn(Dokumen $record) => response()->download('storage/' . $record->file_path, $record->file_name)),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Dokumen $record) {
                        if (Storage::disk('public')->exists($record->file_path))
                            return Storage::disk('public')->delete($record->file_path);
                    }),
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
        ];
    }
}

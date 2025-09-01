<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DibagikanResource\Pages;
use App\Filament\Resources\DibagikanResource\RelationManagers;
use App\Models\Dibagikan;
use App\Models\Divisi;
use App\Models\Dokumen;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
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
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('file_name')
                    ->visibleOn('edit'),
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
                Forms\Components\Toggle::make('is_private')->label('Sembunyikan'),
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
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('size')
                    ->label('Ukuran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diupload')
                    ->date('d M Y')
                    ->sortable()
            ])
            ->filters([
                SelectFilter::make('kategori')
                    ->searchable()
                    ->options(function () {
                        $options = [];
                        $kategori = Dokumen::all()->groupBy('kategori');
                        if (!$kategori->isEmpty())
                            foreach ($kategori as $key => $value) {
                                $options[$key] = $key;
                            }
                        return $options;
                    })
                    ->attribute('kategori'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->hiddenLabel()
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn(Dokumen $record) => response()->download('storage/' . $record->file_path, $record->file_name)),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn() => auth()->user()->role == 'admin')
                        ->after(function (Dokumen $record) {
                            if (Storage::disk('public')->exists($record->file_path))
                                return Storage::disk('public')->delete($record->file_path);
                        }),
                ])->visible(fn() => auth()->user()->role == 'admin')
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
            ])
            ->recordAction('download');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDibagikans::route('/'),
        ];
    }
}

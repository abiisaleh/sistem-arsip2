<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DibagikanResource\Pages;
use App\Filament\Resources\DibagikanResource\RelationManagers;
use App\Models\Dibagikan;
use App\Models\Divisi;
use App\Models\Dokumen;
use App\Models\kategori;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
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

    protected static ?string $navigationGroup = 'Drive';

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $recordTitleAttribute = 'file_name';

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
            ->modifyQueryUsing(fn(Builder $query) => (auth()->user()->role == 'user') ? $query->where('is_private', false) : $query)
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
                Tables\Columns\TextColumn::make('kategori.name')
                    ->label('Folder')
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
                SelectFilter::make('folder')
                    ->searchable()
                    ->options(function () {
                        foreach (Divisi::all() as $divisi)
                            $options[$divisi->judul] = kategori::all()->where('divisi_id', $divisi->id)->pluck('name', 'id')->toArray();
                        return $options;
                    })
                    ->attribute('kategori_id'),
                Filter::make('periode')
                    ->columns(2)
                    ->form([
                        Forms\Components\Select::make('tahun')
                            ->hiddenLabel()
                            ->placeholder('Tahun')
                            ->searchable()
                            ->options(function () {
                                $startYear = 2024;
                                $endYear = now()->year;
                                for ($i = $startYear; $i <= $endYear; $i++)
                                    $options[$i] = $i;

                                return $options;
                            }),
                        Forms\Components\Select::make('bulan')
                            ->hiddenLabel()
                            ->placeholder('Bulan')
                            ->native(false)
                            ->options(function () {
                                for ($i = 1; $i <= 12; $i++)
                                    $options[$i] = Carbon::create(null, $i)->monthName;
                                return $options;
                            })
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['tahun'])
                            $query->whereYear('created_at', $data['tahun']);
                        if ($data['bulan'])
                            $query->whereMonth('created_at', $data['bulan']);
                    })
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

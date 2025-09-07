<?php

namespace App\Filament\Resources\DokumenResource\RelationManagers;

use App\Models\Dokumen;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class DokumenRelationManager extends RelationManager
{
    protected static string $relationship = 'dokumens';

    protected static ?string $title = 'File';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('file_name')
                    ->visibleOn('edit'),
                Forms\Components\FileUpload::make('file_path')
                    ->required()
                    ->label('File')
                    ->hiddenOn('edit')
                    ->previewable(false)
                    ->storeFileNamesIn('file_name')
                    ->directory(function (Get $get) {
                        $divisi = (auth()->user()->role == 'admin') ? Divisi::find($get('divisi_id'))->judul : auth()->user()->divisi->judul;
                        return now()->year . '/' . $divisi . '/' . $get('kategori');
                    })
                    ->multiple(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(null)
            ->recordTitleAttribute('file_name')
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
                Tables\Columns\TextColumn::make('size')
                    ->label('Ukuran'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_private')
                    ->label('Sembunyikan'),
            ])
            ->filters([
                Tables\Filters\Filter::make('periode')
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
                        ->color('warning')
                        ->modalWidth('md'),
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
            ])
            ->recordAction('download');
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratMasukResource\Pages;
use App\Models\SuratMasuk;
use App\Models\Divisi;
use App\Models\User;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\StaticAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;

class SuratMasukResource extends Resource
{
    protected static ?string $model = SuratMasuk::class;

    protected static ?string $navigationGroup = 'Arsip';

    protected static ?string $pluralLabel = 'Surat Masuk';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';

    protected static ?string $recordTitleAttribute = 'nomor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Placeholder::make('Dibuat')
                            ->content(fn(SuratMasuk $record) => $record->created_at),
                        Forms\Components\Placeholder::make('Diverifikasi')
                            ->key('verifikasi-disposisi')
                            ->hintAction(
                                fn(SuratMasuk $record) =>
                                Forms\Components\Actions\Action::make('lihat_disposisi')
                                    ->infolist(function (SuratMasuk $record) {
                                        return Infolist::make()
                                            ->record($record)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('sifat'),
                                                Infolists\Components\TextEntry::make('bagian.nama'),
                                                Infolists\Components\TextEntry::make('isi_disposisi'),
                                                Infolists\Components\TextEntry::make('catatan_disposisi'),
                                            ]);
                                    })
                                    ->modalWidth('md')
                                    ->modalCancelAction(false)
                                    ->modalSubmitAction(false)
                                    ->hidden(is_null($record->file_disposisi))
                                    ->modal()
                            )
                            ->content(fn(SuratMasuk $record) => $record->verified_at ?? 'Menunggu verifikasi'),
                        Forms\Components\Placeholder::make('Diarsipkan')
                            ->content(fn(SuratMasuk $record) => $record->archive_at ?? 'Belum')
                    ])->hiddenOn('create'),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('nomor')
                        ->unique()
                        ->hiddenOn(['edit', 'view'])
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('judul')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('tanggal')
                        ->format('d F Y')
                        ->required(),
                    Forms\Components\Select::make('departemen_id')
                        ->native(false)
                        ->relationship('departemen', 'judul')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('judul'),
                        ])
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Textarea::make('deskripsi')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_private')
                        ->hidden(fn() => auth()->user()->role == 'user')
                        ->onIcon('heroicon-o-lock-closed')
                        ->offIcon('heroicon-o-lock-open')
                        ->required()
                        ->default(false)
                ]),
                AdvancedFileUpload::make('file')
                    ->openable()
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.wordprocessingml.spreadsheetml.sheet'])
                    ->pdfDisplayPage(1)
                    ->pdfToolbar(false)
                    ->pdfNavPanes(false)
                    ->pdfPreviewHeight(450)
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->role == 'user') {
                    $query->whereHas('bagian', function ($query) {
                        $query->whereHas('divisi', function ($query) {
                            $query->where('id', auth()->user()->divisi_id);
                        });
                    })
                        ->where('verified_at', '!=', null)
                        ->where('is_private', false);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->hidden()
                    ->searchable(),
                Tables\Columns\TextColumn::make('departemen.judul')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sifat'),
                Tables\Columns\IconColumn::make('is_private')
                    ->hidden(fn() => auth()->user()->role == 'user')
                    ->boolean(),
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
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSuratMasuks::route('/'),
            'create' => Pages\CreateSuratMasuk::route('/create'),
            'view' => Pages\ViewSuratMasuk::route('/{record}'),
            'edit' => Pages\EditSuratMasuk::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nomor', 'judul', 'deskripsi', 'departemen.judul'];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->role == 'verifikator')
            $record = SuratMasuk::all()->where('verified_at', null)->count();

        if (auth()->user()->role == 'admin')
            $record = SuratMasuk::all()->where('verified_at', '!=', null)->where('archive_at', null)->count();

        if (auth()->user()->role == 'user')
            $record = SuratMasuk::all()->toQuery()->whereHas('bagian', function ($query) {
                $query->whereHas('divisi', function ($query) {
                    $query->where('id', auth()->user()->divisi_id);
                });
            })
                ->where('verified_at', '!=', null)
                ->where('is_private', false)
                ->get()
                ->count();


        return ($record != 0) ? $record : '';
    }
}

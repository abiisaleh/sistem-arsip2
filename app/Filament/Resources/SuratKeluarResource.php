<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratKeluarResource\Pages;
use App\Filament\Resources\SuratKeluarResource\RelationManagers;
use App\Models\SuratKeluar;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratKeluarResource extends Resource
{
    protected static ?string $model = SuratKeluar::class;

    protected static ?string $navigationGroup = 'Arsip';

    protected static ?string $pluralLabel = 'Surat Keluar';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $recordTitleAttribute = 'nomor';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('nomor')
                        ->unique()
                        ->hiddenOn(['edit','view'])
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('judul')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('tanggal')
                        ->required(),
                    Forms\Components\Select::make('divisi_id')
                        ->native(false)
                        ->relationship('divisi','judul')
                        ->createOptionForm([
                            Forms\Components\TextInput::make('judul'),
                        ])
                        ->hidden(auth()->user()->divisi != null)
                        ->searchable()
                        ->preload(),
                    Forms\Components\Textarea::make('deskripsi')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_private')
                        ->onIcon('heroicon-o-lock-closed')
                        ->offIcon('heroicon-o-lock-open')
                        ->required()
                        ->default(false)
                ]),
                AdvancedFileUpload::make('file')
                    ->openable()
                    ->acceptedFileTypes(['application/pdf','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.wordprocessingml.spreadsheetml.sheet'])
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
                if (auth()->user()->divisi_id != null)
                    $query->where('divisi_id', auth()->user()->divisi_id)->orWhere('is_private',false);
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
                Tables\Columns\TextColumn::make('divisi.judul')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name'),
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
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSuratKeluars::route('/'),
            'create' => Pages\CreateSuratKeluar::route('/create'),
            'view' => Pages\ViewSuratKeluar::route('/{record}'),
            'edit' => Pages\EditSuratKeluar::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->nomor." | ".$record->judul;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['nomor','judul','deskripsi','divisi.judul'];
    }
    
    public static function canEdit(Model $record): bool
    {
        return $record->user->id == auth()->user()->id;
    }

    public static function canDelete(Model $record): bool
    {
       return $record->user->id == auth()->user()->id;
    }
    
    public static function canDeleteAny(): bool
    {
        return auth()->user()->divisi == null;
    }
}

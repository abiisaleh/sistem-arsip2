<?php

namespace App\Filament\Resources\DokumenMasukResource\Pages;

use App\Filament\Resources\DokumenMasukResource;
use App\Models\User;
use Asmit\FilamentUpload\Forms\Components\AdvancedFileUpload;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

class ViewDokumenMasuk extends ViewRecord
{
    protected static string $resource = DokumenMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('disposisi')
                ->outlined(fn () => auth()->user()->role == 'admin')
                ->form([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\Group::make([
                                Forms\Components\Select::make('sifat')
                                ->required()
                                ->options([
                                    'segera' => 'Segera',
                                    'sangat-segera' => 'Sangat Segera'
                                ]),
                            Forms\Components\Select::make('divisi_id')
                                ->native(false)
                                ->relationship('divisi','judul')
                                ->multiple()
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('isi_disposisi')
                                ->required()
                                ->autocomplete()
                                ->datalist(fn () => array_keys($this->record->all()->groupBy('isi_disposisi')->toArray())),
                            Forms\Components\Textarea::make('catatan_disposisi'),
                            ]),
                            AdvancedFileUpload::make('file_disposisi')
                                ->hidden(fn () => auth()->user()->role == 'verifikator')
                                ->hintAction( fn () => 
                                    Forms\Components\Actions\Action::make('download-disposisi')
                                        ->url(function () {
                                            $templateProcessor = new TemplateProcessor('assets/format-disposisi.docx');

                                            $data = [
                                                'id' => $this->record->id,

                                                'departemen' => $this->record->departemen->judul,
                                                'nomor' => $this->record->nomor,
                                                'tanggal' => $this->record->tanggal->format('d F Y'),
                                                'deskripsi' => $this->record->deskripsi,

                                                'verified_at' => date('d F Y'),
                                                'name' => User::all()->where('role','verifikator')->first()->name,
                                            ];

                                            foreach ($data as $key => $value)
                                                $templateProcessor->setValue($key,$value);

                                            $fileName = "temp/form-disposisi-{$this->record->nomor}";
                                            $templateProcessor->saveAs("{$fileName}.docx");

                                            return asset("{$fileName}.docx");
                                        })
                                        ->openUrlInNewTab()
                                        ->label('Download Form Disposisi')
                                        ->icon('heroicon-m-arrow-down-tray')
                                )
                                ->acceptedFileTypes(['application/pdf'])
                                ->required(),
                        ])
                ])
                ->action(function (array $data) {
                    if (auth()->user()->role === 'pimpinan') {
                        //generate dokumen disposisi
                    }

                    $data['verified_at'] = now();
                    $this->record->update($data);
                    return Notification::make()->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))->success()->send();
                })
                ->color('info')
                ->hidden(fn () => !is_null($this->record->verified_at)),

            Actions\Action::make('arsip')
                ->action(function () {
                    $this->record->update(['archive_at' => now()]);
                    return Notification::make()->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))->success()->send();
                })
                ->hidden(fn () => is_null($this->record->verified_at) or !is_null($this->record->archive_at))
                ->color('success'),

            Actions\EditAction::make()
                ->hidden(fn () => !is_null($this->record->verified_at)),
        ];
    }
}

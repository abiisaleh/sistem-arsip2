<?php

namespace App\Filament\Resources\DokumenResource\Pages;

use App\Filament\Resources\DokumenResource;
use App\Models\Dokumen;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;

class ListFiles extends ViewRecord
{
    protected static string $resource = DokumenResource::class;

    public function getTitle(): string
    {
        return "Folder {$this->record->name}";
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('upload')
                ->form([
                    Forms\Components\TextInput::make('file_name')
                        ->visibleOn('edit'),
                    Forms\Components\FileUpload::make('file_path')
                        ->required()
                        ->label('File')
                        ->hiddenOn('edit')
                        ->previewable(false)
                        ->storeFileNamesIn('file_name')
                        ->directory(fn() => now()->year . '/' . $this->record->divisi_id . '/' . $this->record->id)
                        ->multiple(),
                ])
                ->mutateFormDataUsing(function ($data) {
                    foreach ($data['file_name'] as $filePath => $fileName) {
                        $file['user_id'] = auth()->id();
                        $file['kategori_id'] = $this->record->id;
                        $file['file_path'] = $filePath;
                        $file['file_name'] = $fileName;
                        $file['created_at'] = now();
                        $file['updated_at'] = now();
                        $files[] = $file;
                    }

                    Dokumen::insert($files);
                    return $data;
                })
                ->label('Upload')
                ->modalWidth('md')
                ->modalHeading('Upload file')
                ->modalSubmitActionLabel('Upload'),
        ];
    }
}

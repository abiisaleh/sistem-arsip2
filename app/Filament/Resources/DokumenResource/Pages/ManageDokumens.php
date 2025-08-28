<?php

namespace App\Filament\Resources\DokumenResource\Pages;

use App\Filament\Resources\DokumenResource;
use App\Models\Divisi;
use App\Models\Dokumen;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Builder;

use function PHPUnit\Framework\isEmpty;

class ManageDokumens extends ManageRecords
{
    protected static string $resource = DokumenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function ($data) {
                    if (auth()->user()->role == 'user')
                        $data['divisi_id'] = auth()->user()->divisi_id;

                    $files = $data['file_name'];
                    $firstFilePath = array_keys($files)[0];
                    $firstFileName = array_shift($files);

                    if (!empty($files)) {
                        foreach ($files as $filePath => $fileName) {
                            $newData = $data;
                            $newData['file_path'] = $filePath;
                            $newData['file_name'] = $fileName;
                            $newData['created_at'] = now();
                            $newData['updated_at'] = now();
                            $datas[] = $newData;
                        }

                        Dokumen::insert($datas);
                    }

                    $data['file_path'] = $firstFilePath;
                    $data['file_name'] = $firstFileName;
                    return $data;
                })
                ->createAnother(false)
                ->label('Upload')
                ->modalWidth('md')
                ->modalHeading('Upload Dokumen')
                ->modalSubmitActionLabel('Upload'),
        ];
    }

    public function getTabs(): array
    {
        if (auth()->user()->role == 'admin') {
            $divisi = Divisi::all()->pluck('judul', 'id');

            foreach ($divisi as $id => $judul) {
                $tab[$judul] = Tab::make()->modifyQueryUsing(fn(Builder $query) => $query->where('divisi_id', $id));
            }

            return $tab;
        }

        return [];
    }
}

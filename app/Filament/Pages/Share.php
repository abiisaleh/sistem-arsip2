<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class Share extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.share';

    protected $files, $folders = [];

    protected $root = null;

    public function __construct()
    {
        $this->files = FacadesStorage::disk('public')->files($this->root);
        $this->folders = FacadesStorage::disk('public')->directories($this->root);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload')
                ->icon('heroicon-m-arrow-down-tray')
        ];
    }

    public function folderAction(): Action
    {
        return Action::make('folder')
            ->form(
                [
                    TextInput::make('name')
                ]
            )
            ->modalWidth('sm')
            ->action(function (array $data) {
                FacadesStorage::disk('public')->makeDirectory($data['name']);
            })
            ->icon('heroicon-o-plus');
    }

    public function searchAction(): Action
    {
        return Action::make('search')
            ->color('gray')
            ->outlined();
    }

    public function sortAction(): Action
    {
        return Action::make('sort')
            ->icon('heroicon-o-plus');
    }

    public function downloadAction(): Action
    {
        return Action::make('download')
            ->extraAttributes(['class' => 'w-full']);
    }

    public function viewAction(): Action
    {
        return Action::make('view')
            ->extraAttributes(['class' => 'w-full'])
            ->color('gray')
            ->outlined();
    }
}

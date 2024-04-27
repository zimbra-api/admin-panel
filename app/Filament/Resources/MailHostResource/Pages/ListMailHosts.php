<?php

namespace App\Filament\Resources\MailHostResource\Pages;

use App\Filament\Resources\MailHostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMailHosts extends ListRecords
{
    protected static string $resource = MailHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pull')
                ->action(function () {
                    
                })
                ->label(__('Pull Data')),
        ];
    }
}

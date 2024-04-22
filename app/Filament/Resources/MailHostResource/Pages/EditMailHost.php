<?php

namespace App\Filament\Resources\MailHostResource\Pages;

use App\Filament\Resources\MailHostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMailHost extends EditRecord
{
    protected static string $resource = MailHostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

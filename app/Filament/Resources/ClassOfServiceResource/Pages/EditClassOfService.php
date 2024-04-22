<?php

namespace App\Filament\Resources\ClassOfServiceResource\Pages;

use App\Filament\Resources\ClassOfServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassOfService extends EditRecord
{
    protected static string $resource = ClassOfServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

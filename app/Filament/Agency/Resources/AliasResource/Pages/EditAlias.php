<?php

namespace App\Filament\Agency\Resources\AliasResource\Pages;

use App\Filament\Agency\Resources\AliasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAlias extends EditRecord
{
    protected static string $resource = AliasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

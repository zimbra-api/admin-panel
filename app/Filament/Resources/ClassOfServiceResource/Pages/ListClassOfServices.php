<?php

namespace App\Filament\Resources\ClassOfServiceResource\Pages;

use App\Filament\Resources\ClassOfServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassOfServices extends ListRecords
{
    protected static string $resource = ClassOfServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

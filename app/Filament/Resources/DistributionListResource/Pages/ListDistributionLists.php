<?php

namespace App\Filament\Resources\DistributionListResource\Pages;

use App\Filament\Resources\DistributionListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDistributionLists extends ListRecords
{
    protected static string $resource = DistributionListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

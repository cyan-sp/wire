<?php

namespace App\Filament\App\Resources\PoolResource\Pages;

use App\Filament\App\Resources\PoolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPools extends ListRecords
{
    protected static string $resource = PoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

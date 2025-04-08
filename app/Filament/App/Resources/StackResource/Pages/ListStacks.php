<?php

namespace App\Filament\App\Resources\StackResource\Pages;

use App\Filament\App\Resources\StackResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStacks extends ListRecords
{
    protected static string $resource = StackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

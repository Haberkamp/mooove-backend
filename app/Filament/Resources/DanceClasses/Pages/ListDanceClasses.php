<?php

namespace App\Filament\Resources\DanceClasses\Pages;

use App\Filament\Resources\DanceClasses\DanceClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDanceClasses extends ListRecords
{
    protected static string $resource = DanceClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

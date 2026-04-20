<?php

namespace App\Filament\Resources\VideoLogs\Pages;

use App\Filament\Resources\VideoLogs\VideoLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVideoLogs extends ListRecords
{
    protected static string $resource = VideoLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

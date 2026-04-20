<?php

namespace App\Filament\Resources\VideoLogs\Pages;

use App\Filament\Resources\VideoLogs\VideoLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVideoLog extends EditRecord
{
    protected static string $resource = VideoLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

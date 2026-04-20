<?php

namespace App\Filament\Resources\VideoLogs\Pages;

use App\Filament\Resources\VideoLogs\VideoLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVideoLog extends CreateRecord
{
    protected static string $resource = VideoLogResource::class;
}

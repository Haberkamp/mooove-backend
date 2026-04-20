<?php

namespace App\Filament\Resources\DanceClasses\RelationManagers;

use App\Filament\Resources\VideoLogs\Tables\VideoLogsTable;
use App\Filament\Resources\VideoLogs\VideoLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class VideoLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'videoLogs';

    protected static ?string $relatedResource = VideoLogResource::class;

    public function table(Table $table): Table
    {
        return VideoLogsTable::configure($table)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

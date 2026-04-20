<?php

namespace App\Filament\Resources\VideoLogs;

use App\Filament\Resources\DanceClasses\DanceClassResource;
use App\Filament\Resources\VideoLogs\Pages\CreateVideoLog;
use App\Filament\Resources\VideoLogs\Pages\EditVideoLog;
use App\Filament\Resources\VideoLogs\Pages\ListVideoLogs;
use App\Filament\Resources\VideoLogs\Schemas\VideoLogForm;
use App\Filament\Resources\VideoLogs\Tables\VideoLogsTable;
use App\Models\VideoLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VideoLogResource extends Resource
{
    protected static ?string $model = VideoLog::class;

    protected static ?string $parentResource = DanceClassResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return VideoLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideoLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideoLogs::route('/'),
            'create' => CreateVideoLog::route('/create'),
            'edit' => EditVideoLog::route('/{record}/edit'),
        ];
    }
}

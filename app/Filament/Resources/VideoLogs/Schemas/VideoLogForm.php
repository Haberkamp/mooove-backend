<?php

namespace App\Filament\Resources\VideoLogs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VideoLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('video')
                    ->required()
                    ->disk('public')
                    ->directory('dance-classes/video-logs')
                    ->visibility('public')
                    ->acceptedFileTypes([
                        'video/mp4',
                        'video/webm',
                        'video/quicktime',
                        'video/x-quicktime',
                    ])
                    ->mimeTypeMap([
                        'mov' => 'video/quicktime',
                    ])
                    ->maxSize(1024 * 1024),
            ]);
    }
}

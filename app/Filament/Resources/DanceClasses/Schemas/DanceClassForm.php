<?php

namespace App\Filament\Resources\DanceClasses\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DanceClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Select::make('instructor_id')
                    ->relationship('instructor', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                FileUpload::make('video')
                    ->required()
                    ->disk('s3')
                    ->directory('dance-classes/videos')
                    ->visibility('private')
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
                TextInput::make('spotify_url')
                    ->label('Spotify song URL')
                    ->required()
                    ->url()
                    ->maxLength(2048),
            ]);
    }
}

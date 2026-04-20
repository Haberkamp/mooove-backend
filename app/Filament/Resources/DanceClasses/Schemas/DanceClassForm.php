<?php

namespace App\Filament\Resources\DanceClasses\Schemas;

use App\Models\DanceCourse;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
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
                Select::make('dance_course_id')
                    ->relationship('danceCourse', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(function ($livewire): ?int {
                        if (! $livewire instanceof RelationManager) {
                            return null;
                        }

                        if ($livewire::getRelationshipName() !== 'danceClasses') {
                            return null;
                        }

                        $owner = $livewire->getOwnerRecord();

                        return $owner instanceof DanceCourse ? $owner->getKey() : null;
                    })
                    ->disabled(fn ($livewire): bool => $livewire instanceof RelationManager
                        && $livewire::getRelationshipName() === 'danceClasses')
                    ->dehydrated(),
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

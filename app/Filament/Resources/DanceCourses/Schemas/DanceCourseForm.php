<?php

namespace App\Filament\Resources\DanceCourses\Schemas;

use App\Models\Instructor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;

class DanceCourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('instructor_id')
                    ->relationship('instructor', 'name')
                    ->label('Teacher')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(function ($livewire): ?int {
                        if (! $livewire instanceof RelationManager) {
                            return null;
                        }

                        if ($livewire::getRelationshipName() !== 'danceCourses') {
                            return null;
                        }

                        $owner = $livewire->getOwnerRecord();

                        return $owner instanceof Instructor ? $owner->getKey() : null;
                    })
                    ->disabled(fn ($livewire): bool => $livewire instanceof RelationManager
                        && $livewire::getRelationshipName() === 'danceCourses')
                    ->dehydrated(),
            ]);
    }
}

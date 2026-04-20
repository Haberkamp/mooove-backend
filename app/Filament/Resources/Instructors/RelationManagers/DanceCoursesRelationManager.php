<?php

namespace App\Filament\Resources\Instructors\RelationManagers;

use App\Filament\Resources\DanceCourses\DanceCourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DanceCoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'danceCourses';

    protected static ?string $relatedResource = DanceCourseResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

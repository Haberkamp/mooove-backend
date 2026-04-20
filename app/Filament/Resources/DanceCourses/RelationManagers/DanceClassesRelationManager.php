<?php

namespace App\Filament\Resources\DanceCourses\RelationManagers;

use App\Filament\Resources\DanceClasses\DanceClassResource;
use App\Filament\Resources\DanceClasses\Tables\DanceClassesTable;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class DanceClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'danceClasses';

    protected static ?string $relatedResource = DanceClassResource::class;

    public function table(Table $table): Table
    {
        return DanceClassesTable::configure($table, includeDanceCourseColumn: false, includeTeacherColumn: false)
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

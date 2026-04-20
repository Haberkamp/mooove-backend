<?php

namespace App\Filament\Resources\DanceCourses;

use App\Filament\Resources\DanceCourses\Pages\CreateDanceCourse;
use App\Filament\Resources\DanceCourses\Pages\EditDanceCourse;
use App\Filament\Resources\DanceCourses\Pages\ListDanceCourses;
use App\Filament\Resources\DanceCourses\RelationManagers\DanceClassesRelationManager;
use App\Filament\Resources\DanceCourses\RelationManagers\StudentsRelationManager;
use App\Filament\Resources\DanceCourses\Schemas\DanceCourseForm;
use App\Filament\Resources\DanceCourses\Tables\DanceCoursesTable;
use App\Models\DanceCourse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DanceCourseResource extends Resource
{
    protected static ?string $model = DanceCourse::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DanceCourseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DanceCoursesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DanceClassesRelationManager::class,
            StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDanceCourses::route('/'),
            'create' => CreateDanceCourse::route('/create'),
            'edit' => EditDanceCourse::route('/{record}/edit'),
        ];
    }
}

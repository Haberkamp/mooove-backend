<?php

namespace App\Filament\Resources\DanceClasses;

use App\Filament\Resources\DanceClasses\Pages\CreateDanceClass;
use App\Filament\Resources\DanceClasses\Pages\EditDanceClass;
use App\Filament\Resources\DanceClasses\Pages\ListDanceClasses;
use App\Filament\Resources\DanceClasses\RelationManagers\VideoLogsRelationManager;
use App\Filament\Resources\DanceClasses\Schemas\DanceClassForm;
use App\Filament\Resources\DanceClasses\Tables\DanceClassesTable;
use App\Models\DanceClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DanceClassResource extends Resource
{
    protected static ?string $model = DanceClass::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMusicalNote;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return DanceClassForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DanceClassesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            VideoLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDanceClasses::route('/'),
            'create' => CreateDanceClass::route('/create'),
            'edit' => EditDanceClass::route('/{record}/edit'),
        ];
    }
}

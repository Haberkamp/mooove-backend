<?php

namespace App\Filament\Resources\DanceCourses\Pages;

use App\Filament\Resources\DanceCourses\DanceCourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDanceCourses extends ListRecords
{
    protected static string $resource = DanceCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

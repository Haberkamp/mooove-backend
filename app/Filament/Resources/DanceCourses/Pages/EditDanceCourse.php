<?php

namespace App\Filament\Resources\DanceCourses\Pages;

use App\Filament\Resources\DanceCourses\DanceCourseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDanceCourse extends EditRecord
{
    protected static string $resource = DanceCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

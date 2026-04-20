<?php

namespace App\Filament\Resources\DanceClasses\Pages;

use App\Filament\Resources\DanceClasses\DanceClassResource;
use App\Filament\Resources\Instructors\InstructorResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditDanceClass extends EditRecord
{
    protected static string $resource = DanceClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('openInstructor')
                ->label(fn (): string => $this->record->instructor->name)
                ->icon(Heroicon::OutlinedUser)
                ->url(fn (): string => InstructorResource::getUrl('edit', ['record' => $this->record->instructor], shouldGuessMissingParameters: true)),
            DeleteAction::make(),
        ];
    }
}

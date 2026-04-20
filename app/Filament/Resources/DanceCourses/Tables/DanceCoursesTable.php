<?php

namespace App\Filament\Resources\DanceCourses\Tables;

use App\Filament\Resources\Instructors\InstructorResource;
use App\Models\DanceCourse;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DanceCoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('instructor'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('instructor.name')
                    ->label('Teacher')
                    ->searchable()
                    ->sortable()
                    ->url(fn (DanceCourse $record): ?string => $record->instructor
                        ? InstructorResource::getUrl('edit', ['record' => $record->instructor], shouldGuessMissingParameters: true)
                        : null),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

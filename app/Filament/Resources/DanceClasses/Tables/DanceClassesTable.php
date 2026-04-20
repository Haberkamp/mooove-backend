<?php

namespace App\Filament\Resources\DanceClasses\Tables;

use App\Filament\Resources\Instructors\InstructorResource;
use App\Models\DanceClass;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DanceClassesTable
{
    public static function configure(
        Table $table,
        bool $includeDanceCourseColumn = true,
        bool $includeTeacherColumn = true,
    ): Table {
        $columns = [
            TextColumn::make('title')
                ->searchable()
                ->sortable(),
        ];

        if ($includeDanceCourseColumn) {
            $columns[] = TextColumn::make('danceCourse.name')
                ->label('Course')
                ->searchable()
                ->sortable();
        }

        if ($includeTeacherColumn) {
            $columns[] = TextColumn::make('danceCourse.instructor.name')
                ->label('Teacher')
                ->searchable()
                ->sortable()
                ->url(fn (DanceClass $record): ?string => $record->danceCourse?->instructor
                    ? InstructorResource::getUrl('edit', ['record' => $record->danceCourse->instructor], shouldGuessMissingParameters: true)
                    : null);
        }

        $columns[] = TextColumn::make('spotify_url')
            ->label('Spotify')
            ->url(fn (?string $state): ?string => $state)
            ->openUrlInNewTab()
            ->limit(40);

        if ($includeDanceCourseColumn || $includeTeacherColumn) {
            $table = $table->modifyQueryUsing(
                fn (Builder $query) => $query->with(['danceCourse.instructor']),
            );
        }

        return $table
            ->columns($columns)
            ->defaultSort('title')
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

<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EpisodesRelationManager extends RelationManager
{
    protected static string $relationship = 'episodes';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lesson_id')
                    ->label('الدرس')
                    ->options(function () {
                        return \App\Models\Lesson::pluck('title', 'id');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('عنوان الحلقة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('وصف الحلقة')
                    ->rows(5)
                    ->nullable(),

                Forms\Components\TextInput::make('video_url')
                    ->label('رابط الفيديو على Vimeo')
                    ->url()
                    ->required(),

                Forms\Components\TextInput::make('episode_order')
                    ->label('ترتيب الحلقة')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lesson.course.title')
                    ->label('الدورة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lesson.title')
                    ->label('الدرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الحلقة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('episode_order')
                    ->label('ترتيب الحلقة')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

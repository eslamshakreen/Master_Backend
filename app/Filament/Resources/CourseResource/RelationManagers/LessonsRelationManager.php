<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $label = 'درس';
    protected static ?string $pluralLabel = 'الدروس';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الدرس')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('وصف الدرس')
                    ->rows(5)
                    ->nullable(),

                Forms\Components\TextInput::make('lesson_order')
                    ->label('ترتيب الدرس')
                    ->numeric()
                    ->default(0),

                Forms\Components\TextInput::make('video_url')
                    ->label('رابط الفيديو على Vimeo')
                    ->url()
                    ->nullable(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الدرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lesson_order')
                    ->label('ترتيب الدرس')
                    ->sortable(),
            ])
            ->filters([
                // إضافة فلاتر إذا لزم الأمر
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة درس جديد'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }
}

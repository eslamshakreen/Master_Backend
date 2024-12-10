<?php

namespace App\Filament\Resources;
use App\Filament\Resources\LessonResource\RelationManagers\EpisodesRelationManager;
use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'الدروس';
    protected static ?string $pluralLabel = 'الدروس';
    protected static ?string $modelLabel = 'درس';
    protected static ?string $slug = 'lessons';

    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'إدارة المحتوى';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('الدورة')
                    ->options(function () {
                        return \App\Models\Course::pluck('title', 'id');
                    })
                    ->searchable()
                    ->required(),

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
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('الدورة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الدرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lesson_order')
                    ->label('ترتيب الدرس')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                // إضافة فلاتر إذا لزم الأمر
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض'),
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
                Tables\Actions\DeleteAction::make()
                    ->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('حذف المحدد'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EpisodesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}

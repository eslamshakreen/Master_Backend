<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Category;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;


use Filament\Forms\Components\Card;


class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'الدورات';
    protected static ?string $pluralLabel = 'الدورات';
    protected static ?string $modelLabel = 'دورة';
    protected static ?string $slug = 'courses';

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'إدارة المحتوى';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('عنوان الدورة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('وصف الدورة')
                    ->rows(5)
                    ->required(),

                Forms\Components\TextInput::make('original_price')
                    ->label('السعر الأصلي')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('discounted_price')
                    ->label('السعر بعد التخفيض')
                    ->numeric()
                    ->nullable(),

                Forms\Components\FileUpload::make('thumbnail')
                    ->label('صورة مصغرة')
                    ->image()
                    ->directory('thumbnails')
                    ->nullable(),

                Forms\Components\Select::make('category_id')
                    ->label('التصنيف')
                    ->options(\App\Models\Category::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('teacher_id')
                    ->label('المدرس')
                    ->options(\App\Models\Teacher::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('number_of_episodes')
                    ->label('عدد الحلقات')
                    ->numeric()
                    ->default(0)
                    ->disabled(),

                Forms\Components\DateTimePicker::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->default(now())
                    ->disabled(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('صورة')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الدورة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teacher.user.name')
                    ->label('المدرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('original_price')
                    ->label('السعر الأصلي')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('discounted_price')
                    ->label('السعر بعد التخفيض')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('number_of_episodes')
                    ->label('عدد الحلقات')
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
            LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}

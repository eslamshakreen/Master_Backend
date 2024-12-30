<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Models\CourseTestimonial;
use Filament\Forms;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;


class TestimonialsRelationManager extends RelationManager
{
    protected static string $relationship = 'testimonials'; // اسم الدالة في نموذج Course
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('job')
                    ->label('الوظيفة')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->required(),
            ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم'),
                Tables\Columns\TextColumn::make('job')->label('الوظيفة'),
                Tables\Columns\TextColumn::make('description')->label('الوصف')->limit(50),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة Testimonial'),
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

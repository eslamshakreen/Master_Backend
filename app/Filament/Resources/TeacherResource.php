<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Models\Teacher;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'المدرسون';
    protected static ?string $pluralLabel = 'المدرسون';
    protected static ?string $modelLabel = 'مدرس';
    protected static ?string $slug = 'teachers';

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'إدارة المستخدمين';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('المستخدم')
                    ->options(function () {
                        return \App\Models\User::where('role', 'teacher')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('bio')
                    ->label('السيرة الذاتية')
                    ->required(),

                Forms\Components\TextInput::make('job_title')
                    ->label('الوظيفة')
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('صورة المستخدم')
                    ->image()
                    ->directory('teachers')
                    ->nullable(),

                Forms\Components\TextInput::make('commission_percentage')
                    ->label('النسبة المئوية للأرباح')
                    ->numeric()
                    ->rules(['min:0', 'max:100'])
                    ->suffix('%')
                    ->required(),

                Forms\Components\TextInput::make('total_earnings')
                    ->label('إجمالي الأرباح')
                    ->numeric()
                    ->default(0)
                    ->disabled(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('اسم المدرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('commission_percentage')
                    ->label('النسبة المئوية للأرباح')
                    ->suffix('%')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_earnings')
                    ->label('إجمالي الأرباح')
                    ->money('USD')
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
            // RelationManagers\CoursesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}

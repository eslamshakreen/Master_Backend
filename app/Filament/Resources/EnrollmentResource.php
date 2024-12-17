<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'الاشتراكات';
    protected static ?string $pluralLabel = 'الاشتراكات';
    protected static ?string $modelLabel = 'اشتراك';
    protected static ?string $navigationGroup = 'إدارة المستخدمين والدورات';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // هنا يمكن للمدير تعديل الحالة
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'active' => 'مفعل'
                    ])
                    ->required(),
                Forms\Components\Select::make('student_id')
                    ->label('المستخدم')
                    ->options(function () {
                        return \App\Models\User::where('role', 'student')->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('course_id')
                    ->label('الدورة')
                    ->options(function () {
                        return \App\Models\Course::pluck('title', 'id');
                    })
                    ->required(),

                Forms\Components\DateTimePicker::make('enrollment_date')
                    ->default(now())
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('الطالب')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('course.title')
                    ->label('الدورة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->searchable()
                    ->sortable()
                    ->color(fn($state) => $state === 'active' ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                // يمكنك إضافة فلاتر حسب الحالة
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل الحالة'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
            'create' => Pages\CreateEnrollment::route('/create'),
            'edit' => Pages\EditEnrollment::route('/{record}/edit'),
        ];
    }
}

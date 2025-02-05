<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'الطلاب المسجلون';
    protected static ?string $pluralLabel = 'الطلاب';
    protected static ?string $modelLabel = 'طالب';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {

        return parent::getEloquentQuery()->where('role', 'student');
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('الاسم')->required(),
                Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email()->required(),
                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(fn(string $context) => $context === 'create')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => !empty($state)),
                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel(),
                Forms\Components\FileUpload::make('image')
                    ->label('الصورة')
                    ->image()
                    ->directory('images')
                    ->nullable(),
                Forms\Components\TextInput::make('age')
                    ->label('العمر')
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('gender')
                    ->label('الجنس')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->label('العنوان'),
                Forms\Components\TextInput::make('country')
                    ->label('البلد'),
                Forms\Components\TextInput::make('phone_2')
                    ->label('رقم الهاتف الثاني')
                    ->tel()
                    ->nullable(),
                Forms\Components\TextInput::make('degree')
                    ->label('الدرجة العلمية'),
                Forms\Components\TextInput::make('company')
                    ->label('الشركة'),
                Forms\Components\TextInput::make('job_title')
                    ->label('المسمى الوظيفي'),
                Forms\Components\TextInput::make('number_of_employees')
                    ->label('عدد الموظفين')
                    ->numeric()
                    ->nullable(),
                // يمكنك إضافة حقول أخرى إذا أردت
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ التسجيل')->dateTime('d/m/Y'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}

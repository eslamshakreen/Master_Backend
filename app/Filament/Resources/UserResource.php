<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'المستخدمون';
    protected static ?string $pluralLabel = 'المستخدمون';
    protected static ?string $modelLabel = 'مستخدم';
    protected static ?string $slug = 'users';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'إدارة المستخدمين';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('الهاتف')
                    ->nullable()
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('address')
                    ->label('العنوان')
                    ->nullable(),

                Forms\Components\Select::make('gender')
                    ->label('الجنس')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'انثى',

                    ]),

                Forms\Components\TextInput::make('age')
                    ->label('العمر')
                    ->nullable(),

                Forms\Components\FileUpload::make('image')
                    ->label('صورة المستخدم')
                    ->image()
                    ->directory('users')
                    ->nullable(),

                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(fn(string $context) => $context === 'create')
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => !empty ($state) ? Hash::make($state) : null)
                    ->dehydrated(fn($state) => !empty ($state)),

                Forms\Components\Select::make('role')
                    ->label('الدور')
                    ->options([
                        'admin' => 'مدير نظام',
                        'teacher' => 'مدرس',
                        'student' => 'طالب',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('role')
                    ->label('الدور')
                    ->formatStateUsing(fn($state) => [
                        'admin' => 'مدير نظام',
                        'teacher' => 'مدرس',
                        'student' => 'طالب',
                    ][$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                // إضافة فلاتر إذا لزم الأمر
            ])
            ->actions([
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
            // إضافة العلاقات إذا لزم الأمر
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }


}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AboutUsResource\Pages;
use App\Models\AboutUs;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class AboutUsResource extends Resource
{
    protected static ?string $model = AboutUs::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'من نحن';
    protected static ?string $pluralLabel = 'من نحن';
    protected static ?string $modelLabel = 'عن المنصة';
    protected static ?string $navigationGroup = 'إدارة الموقع';
    protected static ?int $navigationSort = 10; // ترتيب في القائمة الجانبية

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('العنوان')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\RichEditor::make('description')
                    ->label('الوصف')
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->label('الصورة')
                    ->directory('about_images') // يرفع الصورة لمجلد about_images
                    ->image()
                    ->nullable(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('العنوان')->searchable(),
                Tables\Columns\ImageColumn::make('image')->label('الصورة'),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime('d-m-Y'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAboutUs::route('/'),
            'create' => Pages\CreateAboutUs::route('/create'),
            'edit' => Pages\EditAboutUs::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroResource\Pages;
use App\Models\Hero;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class HeroResource extends Resource
{
    protected static ?string $model = Hero::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'صور الواجهات (Home Page)';
    protected static ?string $pluralLabel = 'صور الواجهة';
    protected static ?string $modelLabel = 'صورة واجهة';
    protected static ?string $slug = 'heroes';
    protected static ?string $navigationGroup = 'إدارة الموقع';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('نوع المحتوى')
                    ->required()
                    ->options([
                        Hero::TYPE_HERO => 'الواجهة',
                        Hero::TYPE_NEWS => 'الاخبار',
                        Hero::TYPE_EVENT => 'الاحداث',
                        Hero::TYPE_ADVERTISEMENT => 'الاعلانات',
                        Hero::TYPE_REVIEW => 'اراء المشتركين',
                        Hero::TYPE_CALL_TO_ACTION => 'تواصل معنا',
                    ])
                    ->reactive(),

                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->maxLength(255)
                    ->visible(fn(callable $get) => $get('type') === Hero::TYPE_REVIEW),

                Forms\Components\TextInput::make('title')
                    ->label('العنوان')
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->rows(5),

                Forms\Components\FileUpload::make('image')
                    ->label('الصورة')
                    ->directory('hero_images')
                    ->image(),

                Forms\Components\TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0)
                    ->helperText('كلما كان الرقم أصغر، ظهرت الصورة في مقدمة العرض.')
                ,


            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('صورة')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('type')
                    ->label('نوع المحتوى')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('العنوان')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->filters([
                // يمكنك إضافة فلاتر إذا لزم الأمر
            ])
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
        return [
            // علاقات إذا لزم الأمر
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroes::route('/'),
            'create' => Pages\CreateHero::route('/create'),
            'edit' => Pages\EditHero::route('/{record}/edit'),
        ];
    }
}

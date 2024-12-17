<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FooterLinkResource\Pages;
use App\Models\FooterLink;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class FooterLinkResource extends Resource
{
    protected static ?string $model = FooterLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?string $navigationLabel = 'روابط الفوتر';
    protected static ?string $pluralLabel = 'روابط الفوتر';
    protected static ?string $modelLabel = 'رابط فوتر';
    protected static ?string $navigationGroup = 'إدارة الموقع';
    protected static ?int $navigationSort = 5;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('icon')
                    ->label('الأيقونة')
                    ->hint('أدخل اسم الأيقونة (في صيغة svg)')
                    ->directory('hero_icons')
                    ->image(),


                Forms\Components\TextInput::make('url')
                    ->label('الرابط')
                    ->required()
                    ->url(),

                Forms\Components\TextInput::make('label')
                    ->label('النص')
                    ->maxLength(255),

                Forms\Components\TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make(name: 'icon')
                    ->label('صورة')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('label')
                    ->label('النص')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('الرابط')
                    ->url(fn($state) => $state, true),

                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),
            ])
            ->filters([
                // إن احتجت فلاتر
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFooterLinks::route('/'),
            'create' => Pages\CreateFooterLink::route('/create'),
            'edit' => Pages\EditFooterLink::route('/{record}/edit'),
        ];
    }
}

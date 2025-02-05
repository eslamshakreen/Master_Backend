<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OurClientResource\Pages;
use App\Filament\Resources\OurClientResource\RelationManagers;
use App\Models\OurClient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OurClientResource extends Resource
{
    protected static ?string $model = OurClient::class;

    protected static ?string $navigationLabel = 'العملاء';
    protected static ?string $pluralLabel = 'العملاء';
    protected static ?string $modelLabel = 'عميل';
    protected static ?string $slug = 'our-client';

    protected static ?string $navigationGroup = 'إدارة الموقع';

    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('صورة مصغرة')
                    ->image()
                    ->directory('our_clients')
                    ->nullable(),

                Forms\Components\TextInput::make('title')
                    ->label('اسم العميل')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('link')
                    ->label('رابط الموقع')
                    ->maxLength(255),

                Forms\Components\TextInput::make('order')
                    ->label('الترتيب')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('صورة')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('اسم العميل')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('link')
                    ->label('رابط الموقع')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListOurClients::route('/'),
            'create' => Pages\CreateOurClient::route('/create'),
            'edit' => Pages\EditOurClient::route('/{record}/edit'),
        ];
    }
}

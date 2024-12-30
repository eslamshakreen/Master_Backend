<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CallResource\Pages;
use App\Models\Call;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Models\User;

class CallResource extends Resource
{
    protected static ?string $model = Call::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'المكالمات';
    protected static ?string $pluralLabel = 'المكالمات';
    protected static ?string $modelLabel = 'مكالمة';
    protected static ?string $slug = 'calls';

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'CRM';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lead_id')
                    ->label('العميل المحتمل')
                    ->relationship('lead', 'name')
                    ->required(),

                Forms\Components\Hidden::make('staff_id')
                    ->default(auth()->id()),

                Forms\Components\TextInput::make('result')
                    ->label('النتيجة')
                    ->required()
                    ->maxLength(255)
                    ->default('no_answer'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.name')
                    ->label(' العميل ')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('staff.name')
                    ->label(' الموظف')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('result')
                    ->label('النتيجة')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('تاريخ التحديث')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('حذف المحدد'),
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
            'index' => Pages\ListCalls::route('/'),
            'create' => Pages\CreateCall::route('/create'),
            'edit' => Pages\EditCall::route('/{record}/edit'),
        ];
    }
}
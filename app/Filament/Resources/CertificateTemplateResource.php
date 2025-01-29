<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateTemplateResource\Pages;
use App\Filament\Resources\CertificateTemplateResource\RelationManagers;
use App\Models\CertificateTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificateTemplateResource extends Resource
{
    protected static ?string $model = CertificateTemplate::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'قوالب الشهادات';
    protected static ?string $pluralLabel = 'قوالب الشهادات';
    protected static ?string $modelLabel = 'قالب الشهادة';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('عنوان القالب')->required(),
            Forms\Components\FileUpload::make('background_path')
                ->label('خلفية الشهادة')
                ->directory('cert_templates')
                ->image()
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('عنوان القالب'),
                Tables\Columns\ImageColumn::make('background_path')->label('الخلفية'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCertificateTemplates::route('/'),
            'create' => Pages\CreateCertificateTemplate::route('/create'),
            'edit' => Pages\EditCertificateTemplate::route('/{record}/edit'),
        ];
    }
}

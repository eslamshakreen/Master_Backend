<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EpisodeResource\Pages;
use App\Models\Episode;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class EpisodeResource extends Resource
{
    protected static ?string $model = Episode::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'الحلقات';
    protected static ?string $pluralLabel = 'الحلقات';
    protected static ?string $modelLabel = 'حلقة';
    protected static ?string $slug = 'episodes';

    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'إدارة المحتوى';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lesson_id')
                    ->label('الدرس')
                    ->relationship('lesson', 'title')
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->label('عنوان الحلقة')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->label('وصف الحلقة')
                    ->rows(5)
                    ->nullable(),

                Forms\Components\TextInput::make('video_url')
                    ->label('رابط الفيديو على Vimeo')
                    ->url()
                    ->required(),

                Forms\Components\FileUpload::make('pdf')
                    ->label('ملف PDF')
                    ->directory('pdf')
                    ->acceptedFileTypes(['application/pdf'])
                    ->nullable(),

                Forms\Components\TextInput::make('episode_order')
                    ->label('ترتيب الحلقة')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lesson.course.title')
                    ->label('الدورة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lesson.title')
                    ->label('الدرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الحلقة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('episode_order')
                    ->label('ترتيب الحلقة')
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
            // إضافة Relation Managers إذا لزم الأمر
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEpisodes::route('/'),
            'create' => Pages\CreateEpisode::route('/create'),
            'edit' => Pages\EditEpisode::route('/{record}/edit'),
        ];
    }
}

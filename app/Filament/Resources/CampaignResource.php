<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationLabel = 'الحملات البريدية';
    protected static ?string $pluralLabel = 'الحملات البريدية';
    protected static ?string $modelLabel = 'حملة بريدية';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('عنوان الحملة')->required(),
            Forms\Components\TextInput::make('subject')->label('موضوع البريد')->required(),
            Forms\Components\RichEditor::make('body')->label('محتوى الرسالة')->required(),
            Forms\Components\FileUpload::make('attachment')
                ->label('ملف مرفق (اختياري)')
                ->directory('campaign_attachments'),
            Forms\Components\Select::make('target')
                ->label('الفئة المستهدفة')
                ->options([
                    'leads' => 'Leads (العملاء المحتملين)',
                    'students' => 'الطلاب',
                    'all' => 'الجميع',
                ])
                ->default('leads')
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->label('عنوان الحملة')->searchable(),
            Tables\Columns\TextColumn::make('target')->label('الفئة المستهدفة'),
            Tables\Columns\TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime('d/m/Y'),
        ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
                // زر لإطلاق الحملة
                Tables\Actions\Action::make('send')
                    ->label('إرسال')
                    ->action(fn($record) => static::sendCampaign($record))
                    ->requiresConfirmation()
                    ->color('primary')
                    ->icon('heroicon-o-paper-airplane'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }

    public static function sendCampaign($record)
    {
        // سنشرح المنطق لاحقاً
        dispatch(new \App\Jobs\SendCampaignEmails($record));
        \Filament\Notifications\Notification::make()
            ->title('نجاح')
            ->body('تم إضافة الحملة إلى طابور الإرسال.')
            ->success()
            ->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}

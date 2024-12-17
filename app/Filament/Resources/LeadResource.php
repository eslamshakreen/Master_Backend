<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'العملاء المحتملون (Leads)';
    protected static ?string $pluralLabel = 'العملاء المحتملون';
    protected static ?string $modelLabel = 'عميل محتمل';
    protected static ?string $navigationGroup = 'CRM';
    protected static ?int $navigationSort = 1;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('الاسم')->required(),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->unique(ignoreRecord: true)
                    ->email()->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('الهاتف')
                    ->nullable()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('تاريخ الإدخال')->dateTime('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_today')
                    ->label('إدخال اليوم')
                    ->query(fn($query) => $query->whereDate('created_at', today())),

                Tables\Filters\Filter::make('has_email')
                    ->label('البريد يحتوي على...')
                    ->form([
                        Forms\Components\TextInput::make('email_contains')->label('الحرف أو النص في البريد'),
                    ])
                    ->query(fn($query, $data) => $query->when($data['email_contains'], fn($q, $val) => $q->where('email', 'like', "%$val%"))),
                Tables\Filters\Filter::make('created_between')
                    ->label('تاريخ الإدخال بين')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')->label('تاريخ البدء'),
                        Forms\Components\DatePicker::make('end_date')->label('تاريخ الانتهاء'),
                    ])
                    ->query(fn($query, $data) => $query->when($data['start_date'] && $data['end_date'], fn($q) => $q->whereBetween('created_at', [$data['start_date'], $data['end_date']]))),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}

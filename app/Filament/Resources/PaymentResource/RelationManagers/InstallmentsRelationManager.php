<?php

namespace App\Filament\Resources\PaymentResource\RelationManagers;

use App\Models\Installment;
use Filament\Forms;
use Filament\Resources\RelationManagers\HasManyRelationManager;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'installments';

    protected static ?string $recordTitleAttribute = 'amount';

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('amount')->label('المبلغ')->numeric()->required(),
            Forms\Components\DateTimePicker::make('paid_at')->label('تاريخ الدفع')->default(now()),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')->label('المبلغ')->formatStateUsing(function ($record) {
                    return number_format($record->amount, 2) . ' LYD';
                }),
                Tables\Columns\TextColumn::make('paid_at')->label('تاريخ الدفع')->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('إضافة قسط جديد'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }
}

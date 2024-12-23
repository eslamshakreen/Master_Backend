<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'الدفعات';
    protected static ?string $pluralLabel = 'الدفعات';
    protected static ?string $modelLabel = 'دفعة';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'إدارة المستخدمين الدفعات';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('enrollment_id')
                ->label('الاشتراك')
                ->options(function () {
                    return \App\Models\Enrollment::with(['student', 'course'])->get()->mapWithKeys(function ($enrollment) {
                        return [$enrollment->id => $enrollment->student->name . ' | ' . $enrollment->course->title];
                    });
                })
                ->required()
                ->reactive()
                // بعد اختيار الاشتراك، نجلب الدورة ونضع قيمتها في total_amount و course_id
                ->afterStateUpdated(function ($state, callable $set) {
                    $enrollment = \App\Models\Enrollment::with('course')->find($state);
                    if ($enrollment && $enrollment->course) {
                        $set('course_id', $enrollment->course_id);
                        // اختيار بين original_price أو discounted_price
                        $price = $enrollment->course->discounted_price ?? $enrollment->course->original_price;
                        $set('total_amount', $price);
                    }
                }),

            Forms\Components\TextInput::make('course_id')
                ->label('الدورة')
                ->disabled()
                ->required(),

            Forms\Components\Select::make('payment_method_id')
                ->label('طريقة الدفع')
                ->options(\App\Models\PaymentMethod::all()->pluck('method_name', 'id')),

            Forms\Components\TextInput::make('total_amount')
                ->numeric()
                ->reactive()
                ->disabled() // لأننا نأخذها من الدورة
                ->default(fn($get) => \App\Models\Course::find($get('course_id'))->discounted_price ?? \App\Models\Course::find($get('course_id'))->original_price ?? 0),

            Forms\Components\TextInput::make('paid_amount')
                ->numeric()
                ->reactive()
                ->default(0),

            Forms\Components\DateTimePicker::make('payment_date')
                ->label('تاريخ الدفع')
                ->default(now())
                ->required(),

            // remaining_amount و teacher_commission نعرض قيمتها من المودل
            // سنجعلها حقول للعرض فقط (disabled) وتعتمد على paid_amount, total_amount, course_id
            Forms\Components\TextInput::make('remaining_amount')
                ->label('المتبقي')
                ->disabled()
                ->dehydrated(false)
                ->reactive()
                ->default(fn($get) => 0)
                ->afterStateHydrated(fn($state, $set, $get) => $set('remaining_amount', self::calculateRemaining($get)))
                ->hiddenOn(['paid_amount', 'total_amount', 'course_id'], function ($set, $get) {
                    $set('remaining_amount', self::calculateRemaining($get));
                }),

            Forms\Components\TextInput::make('teacher_commission')
                ->label('نسبة المدرس')
                ->disabled()
                ->dehydrated(false)
                ->reactive()
                ->default(fn($get) => 0)
                ->hiddenOn(['paid_amount', 'course_id'], function ($set, $get) {
                    $set('teacher_commission', self::calculateTeacherCommission($get));
                }),
        ]);
    }
    private static function calculateRemaining($get)
    {
        $total = $get('total_amount') ?: 0;
        $paid = $get('paid_amount') ?: 0;
        return $total - $paid;
    }

    private static function calculateTeacherCommission($get)
    {
        $courseId = $get('course_id');
        $paid = $get('paid_amount') ?: 0;
        if (!$courseId)
            return 0;
        $course = \App\Models\Course::with('teacher')->find($courseId);
        if (!$course || !$course->teacher)
            return 0;
        $commission = $course->teacher->commission_percentage ?? 0;
        return $paid * ($commission / 100);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')->label('الدورة'),
                Tables\Columns\TextColumn::make('total_amount')->label('المبلغ الكلي')->formatStateUsing(fn($record) => number_format($record->total_amount, 2) . ' LYD'),
                Tables\Columns\TextColumn::make('paid_amount')->label('المدفوع')->formatStateUsing(fn($record) => number_format($record->paid_amount, 2) . 'LYD$'),
                Tables\Columns\TextColumn::make('remaining_amount')->label('المتبقي')->formatStateUsing(fn($record) => number_format($record->remaining_amount, 2) . 'LYD'),
                Tables\Columns\TextColumn::make('teacher_commission')->label('نسبة المدرس')->formatStateUsing(fn($record) => number_format($record->teacher_commission, 2) . ' LYD'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PaymentResource\RelationManagers\InstallmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}

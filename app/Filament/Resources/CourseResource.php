<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers\TestimonialsRelationManager;
use App\Models\Course;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset; // تأكد من توفر Fieldset
use App\Models\Category;
use App\Filament\Resources\CourseResource\RelationManagers\LessonsRelationManager;
use Filament\Forms\Components\Card;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'الدورات';
    protected static ?string $pluralLabel = 'الدورات';
    protected static ?string $modelLabel = 'دورة';
    protected static ?string $slug = 'courses';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'إدارة المحتوى';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        // ===== معلومات الدورة =====
                        Forms\Components\Section::make('معلومات الدورة')
                            ->description('تفاصيل أساسية حول الدورة')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label('عنوان الدورة')
                                        ->required()
                                        ->maxLength(255),

                                    Forms\Components\Textarea::make('description')
                                        ->label('وصف الدورة')
                                        ->rows(5)
                                        ->required(),
                                ]),
                            ]),

                        // ===== تفاصيل السعر =====
                        Forms\Components\Section::make('تفاصيل السعر')
                            ->description('حدد السعر والخصم بالعملتين')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('price_lyd')
                                        ->label('السعر بالدينار الليبي')
                                        ->numeric()
                                        ->default(0),
                                    Forms\Components\TextInput::make('discounted_price_lyd')
                                        ->label('السعر بالدينار الليبي بالخصم')
                                        ->numeric()
                                        ->default(0),

                                    Forms\Components\TextInput::make('price_usd')
                                        ->label('السعر بالدولار الأمريكي')
                                        ->numeric()
                                        ->default(0),
                                    Forms\Components\TextInput::make('discounted_price_usd')
                                        ->label('السعر بالدولار الأمريكي بالخصم')
                                        ->numeric()
                                        ->default(0),

                                    Forms\Components\Toggle::make('is_price_visible')
                                        ->label('عرض السعر')
                                        ->default(true),
                                    Forms\Components\Toggle::make('is_discount_visible')
                                        ->label('عرض الخصم')
                                        ->default(false),
                                ]),
                            ]),

                        // ===== الوسائط =====
                        Forms\Components\Section::make('الوسائط')
                            ->description('صورة مصغرة وفيديو تجريبي')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\FileUpload::make('thumbnail')
                                        ->label('صورة مصغرة')
                                        ->image()
                                        ->directory('thumbnails')
                                        ->nullable(),
                                    Forms\Components\Textarea::make('trial_video')
                                        ->label('فيديو التجريبي')
                                        ->required(),
                                ]),
                            ]),

                        // ===== التصنيفات =====
                        Forms\Components\Section::make('التصنيفات')
                            ->description('تحديد التصنيف والمدرس')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('category_id')
                                        ->label('الاكاديمية')
                                        ->options(\App\Models\Category::pluck('name', 'id'))
                                        ->searchable()
                                        ->required(),

                                    Forms\Components\Select::make('teacher_id')
                                        ->label('المدرس')
                                        ->options(\App\Models\Teacher::with('user')->get()->pluck('user.name', 'id'))
                                        ->searchable()
                                        ->required(),
                                ]),
                            ]),

                        // ===== معلومات إضافية =====
                        Forms\Components\Section::make('معلومات إضافية')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('number_of_episodes')
                                        ->label('عدد الحلقات')
                                        ->numeric()
                                        ->default(0)
                                        ->disabled(),

                                    Forms\Components\DateTimePicker::make('created_at')
                                        ->label('تاريخ الإضافة')
                                        ->default(now())
                                        ->disabled(),
                                ]),
                            ]),

                        // ===== التفاصيل الإضافية (مقسمة لمجموعات) =====
                        Forms\Components\Section::make('تفاصيل إضافية')
                            ->description('أقسام أو رؤوس متعددة مع الصور والروابط')
                            ->schema([

                                // المجموعة الأولى
                                Forms\Components\Fieldset::make('المجموعة الأولى')->schema([
                                    Forms\Components\TextInput::make('headline_one')
                                        ->label('العنوان الرئيسي الأول')
                                        ->nullable(),

                                    Forms\Components\Textarea::make('description_one')
                                        ->label('الوصف الأول')
                                        ->nullable(),

                                    Forms\Components\FileUpload::make('image_one')
                                        ->label('الصورة الأولى')
                                        ->image()
                                        ->directory('images')
                                        ->nullable(),


                                    Forms\Components\TextInput::make('call_to_action_one')
                                        ->label('دعوة للعمل الأولى')
                                        ->nullable(),

                                    Forms\Components\TextInput::make('call_to_action_link_one')
                                        ->label('رابط دعوة للعمل الأولى')
                                        ->nullable(),
                                ])->columns(2),

                                // المجموعة الثانية
                                Forms\Components\Fieldset::make('المجموعة الثانية')->schema([
                                    Forms\Components\TextInput::make('headline_two')
                                        ->label('العنوان الرئيسي الثاني')
                                        ->nullable(),

                                    Forms\Components\Textarea::make('description_two')
                                        ->label('الوصف الثاني')
                                        ->nullable(),

                                    Forms\Components\FileUpload::make('image_two')
                                        ->label('الصورة الثانية')
                                        ->image()
                                        ->directory('images')
                                        ->nullable(),

                                    Forms\Components\TextInput::make('call_to_action_two')
                                        ->label('دعوة للعمل الثانية')
                                        ->nullable(),

                                    Forms\Components\TextInput::make('call_to_action_link_two')
                                        ->label('رابط دعوة للعمل الثانية')
                                        ->nullable(),
                                ])->columns(2),

                                // المجموعة الثالثة
                                Forms\Components\Fieldset::make('المجموعة الثالثة')->schema([
                                    Forms\Components\TextInput::make('headline_three')
                                        ->label('العنوان الرئيسي الثالث')
                                        ->nullable(),

                                    Forms\Components\Textarea::make('description_three')
                                        ->label('الوصف الثالث')
                                        ->nullable(),

                                    Forms\Components\FileUpload::make('image_three')
                                        ->label('الصورة الثالثة')
                                        ->image()
                                        ->directory('images')
                                        ->nullable(),

                                    Forms\Components\TextInput::make('call_to_action_three')
                                        ->label('دعوة للعمل الثالثة')
                                        ->nullable(),

                                    Forms\Components\TextInput::make('call_to_action_link_three')
                                        ->label('رابط دعوة للعمل الثالثة')
                                        ->nullable(),
                                ])->columns(2),
                            ]),
                    ])
                    ->columnSpan('full'),
            ])
            ->columns([
                'sm' => 1,
                'lg' => 1,
            ]);
    }
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('صورة')
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label('عنوان الدورة')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('teacher.user.name')
                    ->label('المدرس')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_lyd')
                    ->label('السعر بالدينار الليبي')
                    ->money('LYD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_usd')
                    ->label('السعر بالدولار الأمريكي')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('number_of_episodes')
                    ->label('عدد الحلقات')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                //
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
            LessonsRelationManager::class,
            TestimonialsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}

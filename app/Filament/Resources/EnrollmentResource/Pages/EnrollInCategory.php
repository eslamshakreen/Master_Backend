<?php

namespace App\Filament\Resources\EnrollmentResource\Pages;

use Filament\Forms;
use Filament\Resources\Pages\Page;
use Filament\Pages\Actions;
use App\Models\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Filament\Resources\EnrollmentResource;
use Filament\Notifications\Notification;

class EnrollInCategory extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    // نربط الصفحة بالـ Resource
    protected static string $resource = EnrollmentResource::class;

    // عنوان يظهر أعلى الصفحة (اختياري)
    protected static ?string $title = 'تسجيل طالب في كل الدورات التابعة الاكادمية';

    // الأهم: تعريف $view ليتجنب الخطأ
    // سننشئ ملف Blade بالمسار المحدد لاحقاً.
    protected static string $view = 'filament.resources.enrollment-resource.pages.enroll-in-category';

    // سنخزن حقول النموذج في property
    public array $data = [];

    // عند التحميل
    public function mount()
    {
        // تعبئة النموذج فارغًا
        $this->form->fill([]);
    }

    // تعريف سكيمة النموذج (العناصر)
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('student_id')
                ->label('الطالب')
                ->options(User::where('role', 'student')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Select::make('category_id')
                ->label('الفئة')
                ->options(Category::pluck('name', 'id'))
                ->searchable()
                ->required(),
        ];
    }

    // هنا نعرّف ما يحدث عند النقر على زر "تسجيل"
    public function submit()
    {
        $data = $this->form->getState();

        $studentId = $data['student_id'];
        $categoryId = $data['category_id'];

        // جلب الدورات التي تنتمي لهذه الفئة
        $courses = Course::where('category_id', $categoryId)->get();

        // إنشاء اشتراك لكل دورة
        foreach ($courses as $course) {
            Enrollment::create([
                'student_id' => $studentId,
                'course_id' => $course->id,
                'enrollment_date' => now(),
                'status' => 'active', // يمكنك تغيير الحالة حسب المطلوب
            ]);
        }

        // رسالة نجاح Filament
        Notification::make()
            ->title('تم تسجيل الطالب في ' . $courses->count() . ' دورة ضمن هذه الاكادمية.')
            ->success()
            ->send();

        // إعادة توجيه لصفحة فهرس الاشتراكات
        return redirect(static::getResource()::getUrl('index'));
    }

    // ربط النموذج بالـ state
    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema($this->getFormSchema())
            ->statePath('data');
    }

    // لو أردنا أزرار (Actions) في أعلى الصفحة
    protected function getActions(): array
    {
        return [
            Actions\ButtonAction::make('submit')
                ->label('تسجيل')
                ->color('primary')
                ->action('submit'),

            Actions\ButtonAction::make('cancel')
                ->label('إلغاء')
                ->url(static::getResource()::getUrl('index')),
        ];
    }
}

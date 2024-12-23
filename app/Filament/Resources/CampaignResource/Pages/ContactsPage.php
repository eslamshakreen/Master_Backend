<?php

namespace App\Filament\Resources\CampaignResource\Pages;
use App\Models\Lead;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables;

class ContactsPage extends Page implements Tables\Contracts\HasTable
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'جميع الأشخاص';
    protected static ?string $title = 'جميع الأشخاص';
    protected static ?string $slug = 'contacts';

    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery()
    {
        // لا يمكننا دمج بسهوله عبر query واحد،
        // سنجلب البيانات يدويا ونضعها في Collection.
        // هذا يتطلب استخدام Table بدون Query، لذا سنستخدم fromArray().

        // جلب leads
        $leads = Lead::select('name', 'email', 'phone')->get()->map(function ($lead) {
            return [
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'type' => 'Lead',
            ];
        });

        // جلب students
        $students = User::where('role', 'student')
            ->select('name', 'email') // نفترض لا يوجد phone في user
            ->get()
            ->map(function ($student) {
                return [
                    'name' => $student->name,
                    'email' => $student->email,
                    'phone' => null,
                    'type' => 'Student',
                ];
            });

        // دمجهم
        $contacts = $leads->concat($students);

        // نستخدم Table Builder من array:
        return $contacts; // هذا سيرجع Collection
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable(),
            Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني')->searchable(),
            Tables\Columns\TextColumn::make('phone')->label('الهاتف'),
            Tables\Columns\TextColumn::make('type')->label('النوع'),
        ];
    }

    // لأننا نستخدم fromArray()، نحتاج لعرض البيانات كأنه Query.
    // InteractsWithTable يتوقع Query Builder او Relation.
    // ولأننا نعيد Collection، نستخدم ->tableData()
    // أحدث إصدارات Filament تسمح ب from() في table.
    // إذا لم تعمل، يمكنك استخدام Table::make() في view لتجاوز الموضوع.

    // في الإصدارات الحديثة، يمكنك عمل:
    protected function getTableData(): array
    {
        return $this->getTableQuery()->toArray();
    }
}

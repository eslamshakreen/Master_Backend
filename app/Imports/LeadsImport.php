<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;

class LeadsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Lead([
            'name' => $row[0] . ' ' . $row[1],
            'email' => $row[2],
            'phone' => $row[4],
            'address' => $row[12],
            'company' => $row[13],
            'position' => $row[14],
            'labels' => $row[15],
            'email_subscriber_status' => $row[18],
            'sms_subscriber_status' => $row[19],
            'last_activity' => $row[20],
        ]);
    }
}

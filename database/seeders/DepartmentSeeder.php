<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments =
            [
                ['name' => 'Отдел разработки портальных решений'],
                ['name' => 'Финансовый отдел'],
                ['name' => 'Отдел технического обеспечения'],
                ['name' => 'Служба безопасности'],
            ];

        collect($departments)->each(fn($department) => Department::create($department));
    }
}

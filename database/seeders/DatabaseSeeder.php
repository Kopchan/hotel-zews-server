<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Reaction;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdminId   = Role::firstOrCreate(['code' => 'admin'  ])->id;
        $roleManagerId = Role::firstOrCreate(['code' => 'manager'])->id;
        $roleUserId    = Role::firstOrCreate(['code' => 'user'   ])->id;

        User::create([
            'phone'      => 9_888_777_6655,
            'password'   => 'admin123',
            'name'       => 'Админ',
            'surname'    => 'Админов',
            'patronymic' => 'Админович',
            'sex'        => 1,
            'birthday'   => '2000-01-01',
            'pass_number'         => '1234567890',
            'pass_issue_date'     => '2010-10-10',
            'pass_birth_address'  => '',
            'pass_authority_name' => '',
            'pass_authority_code' => 777666,
            'role_id' => $roleAdminId
        ]);

        User::create([
            'phone'      => 8_888_111_0000,
            'password'   => 'manager',
            'name'       => 'Манагер',
            'surname'    => 'Манагеров',
            'patronymic' => 'Манагерович',
            'sex'        => 1,
            'birthday'   => '2005-01-01',
            'pass_number'         => '0123456789',
            'pass_issue_date'     => '2015-10-10',
            'pass_birth_address'  => '',
            'pass_authority_name' => '',
            'pass_authority_code' => 777666,
            'role_id' => $roleManagerId
        ]);

        User::create([
            'phone'      => 9_876_543_2100,
            'password'   => 'test1234',
            'name'       => 'Тест',
            'surname'    => 'Тестова',
            'patronymic' => 'Тестовна',
            'sex'        => 0,
            'birthday'   => '2005-01-01',
            'pass_number'         => '1123456789',
            'pass_issue_date'     => '2015-10-10',
            'pass_birth_address'  => '',
            'pass_authority_name' => '',
            'pass_authority_code' => 777666,
            'role_id' => $roleUserId
        ]);
    }
}

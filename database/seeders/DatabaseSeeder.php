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
        $roleId = Role::firstOrCreate(['code' => 'admin'])->id;

        User::create([
            'phone'      => '98887776655',
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
            'role_id' => $roleId
        ]);
    }
}

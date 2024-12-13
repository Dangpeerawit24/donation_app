<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
  
class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
               'name'=>'พีรวิชญ์ จันตา',
               'email'=>'dang.peerawit24@gmail.com',
               'type'=>1,
               'password'=> bcrypt('Dang_peerawit24'),
            ],
            [
               'name'=>'Member',
               'email'=>'member@member.com',
               'type'=> 2,
               'password'=> bcrypt('member1234'),
            ],
            [
               'name'=>'User',
               'email'=>'user@user.com',
               'type'=>0,
               'password'=> bcrypt('user1234'),
            ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}

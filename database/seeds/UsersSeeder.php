<?php
 use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        factory(User::class)
            ->create([
                'name' => 'John',
                'username' => 'John',
                'email' => 'john@gmail.com',
                'password' => bcrypt('john@gmail.com')
            ]);
    }
}

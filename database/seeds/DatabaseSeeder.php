<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        $packages = [
            [
                'name' => 'Jibambe',
                'description' => 'Get connected for Two hours',
                'amount' => 20,
            ],
            [
                'name' => 'More Fun',
                'description' => 'Unlimited fun for 4 hours!',
                'amount' => 30,
            ],
            [
                'name' => 'Kachwani',
                'description' => 'Ten hours of uninterrupted fun! It only gets better!',
                'amount' => 50,
            ],
            [
                'name' => 'Inua Soo',
                'description' => 'How much fun can you have online for 12 hours? Find out!',
                'amount' => 100,
            ],
            [
                'name' => 'Shtua Watu',
                'description' => 'Hop onto the 24 hour non-stop internet with unlimited browsing!',
                'amount' => 150,
            ]
        ];

        for ($i = 0; $i<5; ++$i)
        {
            factory(App\Package::class)->create($packages[$i]);
        }

    }
}

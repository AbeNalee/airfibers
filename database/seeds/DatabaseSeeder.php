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
                'duration' => 120,
            ],
            [
                'name' => 'More Fun na Chwani',
                'description' => 'Unlimited fun for Six hours!',
                'validity' => 'valid for 6 hours',
                'amount' => 50,
                'duration' => 360,
            ],
            [
                'name' => 'Half day',
                'description' => 'Twelve hours of uninterrupted fun! It only gets better!',
                'validity' => 'valid for 12 hours',
                'amount' => 80,
                'duration' => 720,
            ],
            [
                'name' => 'Inua Soo',
                'description' => 'How much fun can you have online for 24 hours? Find out!',
                'validity' => 'valid for one day',
                'amount' => 100,
                'duration' => 1440,
            ],
            [
                'name' => 'Shtua Watu',
                'description' => 'Hop onto the full week, non-stop, unlimited internet!',
                'validity' => 'valid for one week',
                'amount' => 600,
                'duration' => 10080,
            ],
            [
                'name' => 'Monthly unlimited',
                'description' => 'Get unlimited connection for a whole month!',
                'validity' => 'valid for one month',
                'amount' => 2000,
                'duration' => 43200,
            ],
            [
                'name' => 'Daily 100mb',
                'description' => 'For only 15 bob, get 100mb valid for 24 hours',
                'validity' => 'valid for 24 hours',
                'amount' => 15,
                'duration' => 1440,
                'quota_based' => true,
                'm_bytes' => 100,
            ],
            [
                'name' => 'Daily 500mb',
                'description' => 'For only 30 bob, get 500mb valid for 24 hours',
                'validity' => 'valid for 24 hours',
                'amount' => 30,
                'duration' => 1440,
                'quota_based' => true,
                'm_bytes' => 500,
            ],
            [
                'name' => 'Daily 1Gb',
                'description' => 'For only 50 bob, get 1Gb valid for 24 hours',
                'validity' => 'valid for 24 hours',
                'amount' => 50,
                'duration' => 1440,
                'quota_based' => true,
                'm_bytes' => 1024,
            ],
            [
                'name' => 'Weekly 500mb',
                'description' => 'For only 50 bob, get 500mb valid for one week',
                'validity' => 'valid for one week',
                'amount' => 50,
                'duration' => 10080,
                'quota_based' => true,
                'm_bytes' => 500,
            ],
            [
                'name' => 'Weekly 1.5Gb',
                'description' => 'For only 100 bob, get 1.5Gb valid for one week',
                'validity' => 'valid for one week',
                'amount' => 100,
                'duration' => 10080,
                'quota_based' => true,
                'm_bytes' => 1524,
            ],
            [
                'name' => 'Monthly 5Gb',
                'description' => 'For only 350 bob, get 5Gb valid for 30 days!',
                'validity' => 'valid for one month',
                'amount' => 350,
                'duration' => 43200,
                'quota_based' => true,
                'm_bytes' => 5120,
            ],
            [
                'name' => 'Monthly 10Gb',
                'description' => 'For only 500 bob, get 10Gb valid for 30 days!',
                'validity' => 'valid for one month',
                'amount' => 500,
                'duration' => 43200,
                'quota_based' => true,
                'm_bytes' => 10240,
            ],

        ];

        for ($i = 0; $i<13; ++$i)
        {
            factory(App\Package::class)->create($packages[$i]);
        }

    }
}

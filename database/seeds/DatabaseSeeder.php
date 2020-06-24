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
//        $this->call(UsersTableSeeder::class);
        $packages = [
            [
                'name' => '2 Hours Unlimited',
                'description' => 'Get connected for Two hours',
                'amount' => 20,
                'validity' => 'valid for 2 hours',
                'duration' => 120,
            ],
            [
                'name' => '6 Hours Unlimited',
                'description' => 'Unlimited fun for Six hours!',
                'validity' => 'valid for 6 hours',
                'amount' => 50,
                'duration' => 360,
            ],
            [
                'name' => '12 Hours Unlimited',
                'description' => 'Twelve hours of uninterrupted fun! It only gets better!',
                'validity' => 'valid for 12 hours',
                'amount' => 80,
                'duration' => 720,
            ],
            [
                'name' => '24 Hours Unlimited',
                'description' => 'How much fun can you have online for 24 hours? Find out!',
                'validity' => 'valid for one day',
                'amount' => 100,
                'duration' => 1440,
            ],
            [
                'name' => 'Weekly Unlimited',
                'description' => 'Hop onto the full week, non-stop, unlimited internet!',
                'validity' => 'valid for one week',
                'amount' => 600,
                'duration' => 10080,
            ],
            [
                'name' => 'Monthly Unlimited',
                'description' => 'Get unlimited connection for a whole month!',
                'validity' => 'valid for one month',
                'amount' => 2000,
                'duration' => 43200,
            ],
            [
                'name' => 'Monthly Premium',
                'description' => 'Get unlimited connection for a whole month at incredible speeds!',
                'validity' => 'valid for one month',
                'amount' => 3000,
                'up' => 6000,
                'down' => 6000,
                'duration' => 43200,
            ],
            [
                'name' => 'Daily 100MB',
                'description' => 'For only 15 bob, get 100MB valid for 24 hours',
                'validity' => 'valid for 24 hours',
                'amount' => 15,
                'duration' => 1440,
                'quota_based' => true,
                'm_bytes' => 110,
            ],
            [
                'name' => 'Daily 500MB',
                'description' => 'For only 30 bob, get 500MB valid for 24 hours',
                'validity' => 'valid for 24 hours',
                'amount' => 30,
                'duration' => 1440,
                'quota_based' => true,
                'm_bytes' => 510,
            ],
            [
                'name' => 'Daily 1GB',
                'description' => 'For only 50 bob, get 1GB valid for 24 hours',
                'validity' => 'valid for 24 hours',
                'amount' => 50,
                'duration' => 1440,
                'quota_based' => true,
                'm_bytes' => 1034,
            ],
            [
                'name' => 'Weekly 500MB',
                'description' => 'For only 50 bob, get 500MB valid for one week',
                'validity' => 'valid for one week',
                'amount' => 50,
                'duration' => 10080,
                'quota_based' => true,
                'm_bytes' => 510,
            ],
            [
                'name' => 'Weekly 1.5GB',
                'description' => 'For only 100 bob, get 1.5GB valid for one week',
                'validity' => 'valid for one week',
                'amount' => 100,
                'duration' => 10080,
                'quota_based' => true,
                'm_bytes' => 1534,
            ],
            [
                'name' => 'Monthly 5GB',
                'description' => 'For only 350 bob, get 5GB valid for 30 days!',
                'validity' => 'valid for one month',
                'amount' => 350,
                'duration' => 43200,
                'quota_based' => true,
                'm_bytes' => 5130,
            ],
            [
                'name' => 'Monthly 10GB',
                'description' => 'For only 500 bob, get 10GB valid for 30 days!',
                'validity' => 'valid for one month',
                'amount' => 500,
                'duration' => 43200,
                'quota_based' => true,
                'm_bytes' => 10250,
            ],
            [
                'name' => 'Weekend Offer',
                'description' => 'Get 72 hours for only 200 bob',
                'validity' => 'valid for an entire weekend (72 hours)',
                'amount' => 200,
                'duration' => 4320,
                'quota_based' => true,
            ],
            [
                'name' => 'One Hour Unlimited',
                'description' => 'Get unlimited one hour for only 20 bob',
                'validity' => 'valid for an hour',
                'amount' => 20,
                'duration' => 60,
                'quota_based' => false,
            ],
            [
                'name' => 'Three Hours Unlimited',
                'description' => 'Get unlimited 3 hours for only 50 bob',
                'validity' => 'valid for 3 hours',
                'amount' => 50,
                'duration' => 180,
                'quota_based' => false,
            ]

        ];

//        for ($i = 0; $i<14; ++$i)
//        {
//            factory(App\Package::class)->create($packages[$i]);
//        }
        for ($i=15; $i<17; ++$i)
        {
            factory(App\Package::class)->create($packages[$i]);
        }

    }
}

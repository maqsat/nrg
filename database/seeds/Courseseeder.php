<?php

use Illuminate\Database\Seeder;
use App\Models\MobileApp\Course;
use Faker\Factory as Faker;

class Courseseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1,20) as $index) {
            Course::insert([
                'title' => $faker->name,
                'description' => $faker->realText($maxNbChars = 100, $indexSize = 2),
                'preview' => 'storage/images/2019/08/27/phpZIxhan.jpg',
            ]);
        }
    }
}

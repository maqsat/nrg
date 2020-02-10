<?php

use Illuminate\Database\Seeder;
use App\Models\MobileApp\Recommendation;
use Faker\Factory as Faker;

class Recommendationseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $d=11;
        foreach (range(1,20) as $index) {
            $d+=1;
            Recommendation::insert([
                'course_id' => $d,
                'title' => $faker->realText($maxNbChars = 20, $indexSize = 2),
                'description' => $faker->realText($maxNbChars = 100, $indexSize = 2),
                'file' => 'storage/images/2019/08/28/phpjPJg4r.png',
            ]);
        }
    }
}

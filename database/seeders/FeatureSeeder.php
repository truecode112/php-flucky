<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Feature::create([
            'title' => 'TEXT_CHAT',
            'description' => 'Text Chat',
            'status' => 'active',
            'paid' => 'no',
        ]);
        Feature::create([
            'title' => 'VIDEO_CHAT',
            'description' => 'Video Chat',
            'status' => 'active',
            'paid' => 'no',
        ]);
        Feature::create([
            'title' => 'GENDER_FILTER',
            'description' => 'Gender Filter',
            'status' => 'active',
            'paid' => 'no',
        ]);
        Feature::create([
            'title' => 'COUNTRY_FILTER',
            'description' => 'Country Filter',
            'status' => 'active',
            'paid' => 'no',
        ]);
        Feature::create([
            'title' => 'FAKE_VIDEO',
            'description' => 'Fake Video',
            'status' => 'inactive',
            'paid' => 'no',
        ]);
    }
}

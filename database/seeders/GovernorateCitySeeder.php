<?php

namespace Database\Seeders;

use App\Models\Governorate;
use App\Models\City;
use App\Models\University;
use Illuminate\Database\Seeder;

class GovernorateCitySeeder extends Seeder
{
    public function run(): void
    {
        $governorate = Governorate::firstOrCreate(['name' => 'بني سويف']);

        $cities = [
            'بني سويف',
            'الواسطى',
            'ناصر',
            'إهناسيا',
            'ببا',
            'سمسطا',
            'الفشن',
        ];

        foreach ($cities as $cityName) {
            City::firstOrCreate([
                'governorate_id' => $governorate->id,
                'name' => $cityName,
            ]);
        }

        $beniSuefCity = City::where('governorate_id', $governorate->id)
            ->where('name', 'بني سويف')
            ->first();

        $universities = [
            'جامعة بني سويف',
            'المعهد العالي للهندسة والتكنولوجيا ببني سويف',
        ];

        foreach ($universities as $universityName) {
            University::firstOrCreate([
                'city_id' => $beniSuefCity->id,
                'name' => $universityName,
            ]);
        }
    }
}

<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Material', 'code' => 'MT'],
            ['name' => 'Pekerja', 'code' => 'PJ'],
            ['name' => 'Elektrika', 'code' => 'EL'],
            ['name' => 'Peralatan', 'code' => 'EQ'],
            ['name' => 'HSE', 'code' => 'HS'],
        ];
        foreach ($data as $item) {
            Category::firstOrCreate(['code' => $item['code']], $item);
        }
    }
} 
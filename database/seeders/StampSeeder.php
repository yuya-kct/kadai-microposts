<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stamp;

class StampSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Stamp::create([
            'name' => 'いいね',
            'image_path' => 'images/like.png', // public/images/like.pngに画像を配置
        ]);

        Stamp::create([
            'name' => 'すごい',
            'image_path' => 'images/surprised.png', // public/images/wow.pngに画像を配置
        ]);

        Stamp::create([
            'name' => 'はてな',
            'image_path' => 'images/thinking.png', // public/images/wow.pngに画像を配置
        ]);
    }
}

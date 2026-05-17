<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Destination;
use App\Enums\DestinationStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name'     => 'Admin Jelajah Jogja',
            'email'    => 'example@example.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $categories = ['Alam', 'Budaya', 'Kuliner', 'Belanja', 'Sejarah'];
        foreach ($categories as $cat) {
            Category::create(['name' => $cat, 'slug' => Str::slug($cat)]);
        }

        $destinations = [
            ['title' => 'Pantai Parangtritis', 'district' => 'Bantul', 'category' => 'Alam',
             'description' => 'Pantai ikonik di selatan Jogja dengan pasir hitam dan ombak besar yang memukau.'],
            ['title' => 'Candi Prambanan', 'district' => 'Sleman', 'category' => 'Sejarah',
             'description' => 'Kompleks candi Hindu terbesar di Indonesia, warisan budaya dunia UNESCO.'],
            ['title' => 'Malioboro', 'district' => 'Kota Yogyakarta', 'category' => 'Belanja',
             'description' => 'Jantung kota Jogja, surga belanja batik, kerajinan, dan oleh-oleh khas.'],
            ['title' => 'Gunung Merapi', 'district' => 'Sleman', 'category' => 'Alam',
             'description' => 'Gunung berapi aktif paling berbahaya di Indonesia dengan pemandangan luar biasa.'],
            ['title' => 'Keraton Yogyakarta', 'district' => 'Kota Yogyakarta', 'category' => 'Budaya',
             'description' => 'Istana Sultan Yogyakarta yang masih aktif dan menjadi pusat budaya Jawa.'],
        ];

        foreach ($destinations as $i => $data) {
            $cat = Category::where('name', $data['category'])->first();
            Destination::create([
                'category_id'     => $cat->id,
                'submitter_name'  => 'Admin',
                'submitter_email' => 'admin@jelajahjogja.com',
                'title'           => $data['title'],
                'slug'            => Str::slug($data['title']),
                'description'     => $data['description'],
                'address'         => $data['district'] . ', Yogyakarta',
                'district'        => $data['district'],
                'status'          => DestinationStatus::Approved,
                'is_featured'     => $i < 3,
                'published_at'    => now(),
            ]);
        }
    }
}

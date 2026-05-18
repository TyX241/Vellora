<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $now = Carbon::now();

        // 1. Membuat 25 User
        $users = [];
        $password = Hash::make('password123'); // Semua akun menggunakan password yang sama
        for ($i = 1; $i <= 25; $i++) {
            $users[] = [
                'username' => $faker->unique()->userName(),
                'email' => $faker->unique()->safeEmail(),
                'password' => $password,
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('users')->insert($users);

        // 2. Membuat 500 Data Film & Series
        $media = [];
        $statusTayang = ['Ongoing', 'Completed'];
        
        for ($i = 1; $i <= 500; $i++) {
            $isSeries = $faker->boolean(60); // 60% peluang berupa series (misal: anime/drakor)
            
            $media[] = [
                'judul' => ucwords($faker->words(random_int(1, 4), true)),
                'format_tayangan' => $isSeries ? 'Series' : 'Film',
                'negara_asal' => $faker->randomElement(['Jepang', 'Korea Selatan', 'Amerika Serikat', 'Indonesia']),
                'is_animation' => $faker->boolean(40),
                'deskripsi' => $faker->paragraph(),
                'poster_url' => 'https://via.placeholder.com/300x450?text=Poster+' . $i,
                'tanggal_rilis' => $faker->date(),
                'status_tayang' => $isSeries ? $faker->randomElement($statusTayang) : 'Completed',
                'total_episode' => $isSeries ? $faker->numberBetween(12, 100) : 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        
        // Insert data media secara bertahap (chunk) agar tidak membebani memori
        foreach (array_chunk($media, 100) as $chunk) {
            DB::table('media')->insert($chunk);
        }

        // 3. Membuat Watchlist & Review (Section Populer)
        $watchlists = [];
        $reviews = [];
        
        for ($userId = 1; $userId <= 25; $userId++) {
            // Memilih tayangan acak untuk tiap user (20-40 tontonan)
            $watchedMedia = $faker->randomElements(range(1, 500), $faker->numberBetween(20, 40));
            
            // Rekayasa agar Media ID 1-30 sering ditonton oleh semua user (untuk mengisi section populer)
            $popularMedia = $faker->randomElements(range(1, 30), 15);
            $allUserMedia = array_unique(array_merge($watchedMedia, $popularMedia));
            
            foreach ($allUserMedia as $mediaId) {
                $statusWatch = $faker->randomElement(['Plan to Watch', 'Watching', 'Completed', 'Dropped']);
                
                $watchlists[] = [
                    'user_id' => $userId,
                    'media_id' => $mediaId,
                    'status' => $statusWatch,
                    'progres_episode' => $statusWatch === 'Completed' ? null : $faker->numberBetween(1, 10),
                    'waktu_diubah' => $now,
                ];
                
                // Berikan ulasan/review untuk tayangan yang sudah 'Completed' atau secara acak
                if ($statusWatch === 'Completed' || $faker->boolean(30)) {
                    $reviews[] = [
                        'user_id' => $userId,
                        'media_id' => $mediaId,
                        'rating' => $faker->randomFloat(1, 5, 10), // Rating antara 5.0 - 10.0
                        'komentar' => $faker->sentence(),
                        'waktu_dibuat' => $now,
                    ];
                }
            }
        }
        
        // Insert watchlist dan ulasan
        foreach (array_chunk($watchlists, 100) as $chunk) {
            DB::table('watchlists')->insert($chunk);
        }
        foreach (array_chunk($reviews, 100) as $chunk) {
            DB::table('reviews')->insert($chunk);
        }

        // 4. Membuat Playlist dan Item Playlist
        $playlists = [];
        $playlistItems = [];
        $playlistIdCounter = 1;
        
        for ($userId = 1; $userId <= 25; $userId++) {
            $numPlaylists = $faker->numberBetween(1, 3); // Tiap user punya 1-3 playlist
            
            for ($p = 0; $p < $numPlaylists; $p++) {
                $playlists[] = [
                    'user_id' => $userId,
                    'nama_playlist' => $faker->words(2, true) . ' Collection',
                    'deskripsi' => $faker->sentence(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                
                // Isi playlist dengan 5-15 tayangan acak
                $items = $faker->randomElements(range(1, 500), $faker->numberBetween(5, 15));
                foreach ($items as $mediaId) {
                    $playlistItems[] = [
                        'playlist_id' => $playlistIdCounter,
                        'media_id' => $mediaId,
                        'waktu_ditambahkan' => $now,
                    ];
                }
                $playlistIdCounter++;
            }
        }
        
        // Insert playlists dan itemnya
        DB::table('playlists')->insert($playlists);
        foreach (array_chunk($playlistItems, 100) as $chunk) {
            DB::table('playlist_items')->insert($chunk);
        }
    }
}
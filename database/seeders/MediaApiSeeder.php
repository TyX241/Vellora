<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MediaApiSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $media = [];
        $mediaId = 1;

        $this->command->info('Mengambil 125 Film Anime (Bebas Blokir)...');
        
        // 1. Mengambil Anime Movies dari Jikan API
        for ($page = 1; $page <= 5; $page++) {
            // URL menggunakan parameter type=movie
            $response = Http::get("https://api.jikan.moe/v4/top/anime?type=movie&limit=25&page={$page}");
            
            if ($response->successful() && isset($response['data'])) {
                foreach ($response['data'] as $movie) {
                    $media[] = [
                        'media_id' => $mediaId++,
                        'judul' => $movie['title'],
                        'format_tayangan' => 'Film',
                        'negara_asal' => 'Jepang',
                        'is_animation' => true,
                        'deskripsi' => substr($movie['synopsis'] ?? 'Tidak ada deskripsi.', 0, 500),
                        'poster_url' => $movie['images']['jpg']['large_image_url'] ?? null,
                        'tanggal_rilis' => isset($movie['aired']['from']) ? substr($movie['aired']['from'], 0, 10) : null,
                        'status_tayang' => 'Completed',
                        'total_episode' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            // Penting: Jeda 2 detik agar tidak diblokir sementara oleh server Jikan (Rate Limit)
            sleep(2); 
        }

        $this->command->info('Mengambil 125 Anime Series...');
        
        // 2. Mengambil Anime Series dari Jikan API
        for ($page = 1; $page <= 5; $page++) {
            // URL menggunakan parameter type=tv
            $response = Http::get("https://api.jikan.moe/v4/top/anime?type=tv&limit=25&page={$page}");
            
            if ($response->successful() && isset($response['data'])) {
                foreach ($response['data'] as $anime) {
                    $media[] = [
                        'media_id' => $mediaId++,
                        'judul' => $anime['title'],
                        'format_tayangan' => 'Series',
                        'negara_asal' => 'Jepang',
                        'is_animation' => true,
                        'deskripsi' => substr($anime['synopsis'] ?? 'Tidak ada deskripsi.', 0, 500),
                        'poster_url' => $anime['images']['jpg']['large_image_url'] ?? null,
                        'tanggal_rilis' => isset($anime['aired']['from']) ? substr($anime['aired']['from'], 0, 10) : null,
                        'status_tayang' => $anime['status'] === 'Finished Airing' ? 'Completed' : 'Ongoing',
                        'total_episode' => $anime['episodes'] ?? null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
            sleep(2);
        }

        // Insert ke database
        foreach (array_chunk($media, 50) as $chunk) {
            DB::table('media')->insert($chunk);
        }
        
        $this->command->info('SUKSES! 250 Media dengan poster asli berhasil dimasukkan.');
    }
}
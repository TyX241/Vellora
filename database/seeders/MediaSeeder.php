<?php

namespace Database\Seeders;

use App\Models\Media;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $mediaData = [
            // 1. Anime (Sedang Tayang / Ongoing)
            [
                'judul' => 'Demon Slayer: Hashira Training Arc',
                'format_tayangan' => 'Series',
                'negara_asal' => 'Jepang',
                'is_animation' => true,
                'deskripsi' => 'Para Hashira melatih anggota Kisatsutai untuk bersiap menghadapi pertempuran akhir melawan Muzan Kibutsuji.',
                'poster_url' => 'https://myanimelist.net/images/anime/1565/142711.jpg',
                'tanggal_rilis' => Carbon::now()->subDays(2), 
                'status_tayang' => 'Ongoing',
                'total_episode' => 12,
            ]
        ];

        foreach ($mediaData as $media) {
            Media::create($media);
        }
    }
}
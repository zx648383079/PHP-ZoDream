<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Module\OnlineTV\Domain\Models\LiveModel;
use Module\OnlineTV\Domain\Models\MovieModel;
use Module\OnlineTV\Domain\Models\MusicModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $live_count = LiveModel::query()->where('status', 1)->count();
        $music_count = MusicModel::query()->count();
        $music_today = $music_count < 1 ? 0 : MusicModel::where('created_at', '>=', $todayStart)->count();
        $movie_count = MovieModel::query()->count();
        $movie_today = $movie_count < 1 ? 0 : MovieModel::where('created_at', '>=', $todayStart)->count();

        return compact('live_count', 'music_count', 'music_today',
            'movie_count', 'movie_today');
    }
}
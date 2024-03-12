<?php
declare(strict_types=1);
namespace Module\Note\Domain\Repositories;


use Module\Note\Domain\Model\NoteModel;

final class StatisticsRepository {

    public static function subtotal(): array {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $note_count = NoteModel::query()->count();
        $note_today = $note_count < 1 ? 0 : NoteModel::where('created_at', '>=', $todayStart)->count();
        return compact('note_count', 'note_today');
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => '便签数量',
                'count' => NoteModel::where('user_id', $user)->count(),
                'unit' => '条',
            ],
        ];
    }
}
<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Module\Blog\Domain\Model\BlogClickLogModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TermModel;

final class StatisticsRepository {

    public static function subtotal(int $user = 0): array {
        $term_count = TermModel::query()->count();
        if ($user > 0) {
            $blogIds = BlogModel::where('user_id', $user)->pluck('id');
            $blog_count = count($blogIds);
            $view_count = BlogModel::where('user_id', $user)->sum('click_count');
            $comment_count = CommentModel::whereIn('blog_id', $blogIds)
                ->count();
            return compact('term_count', 'blog_count', 'view_count', 'comment_count');
        }
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $blog_count = BlogModel::query()->count();
        $blog_today = BlogModel::where('created_at', '>=', $todayStart)->count();
        $view_count = BlogModel::query()->sum('click_count');
        $view_today = BlogClickLogModel::where('happen_day', date('Y-m-d'))->sum('click_count');
        $comment_count = CommentModel::query()->count();
        $comment_today = CommentModel::where('created_at', '>=', $todayStart)->count();
        return compact('term_count',
            'blog_count', 'blog_today',
            'view_count', 'view_today',
            'comment_count', 'comment_today');
    }

    public static function subtotalMap(int $user = 0): array {
        $data = static::subtotal($user);
        return [
            [
                'name' => '分类',
                'icon' => 'fa-folder',
                'count' => $data['term_count']
            ],
            [
                'name' => '文章',
                'icon' => 'fa-file',
                'count' => $data['blog_count']
            ],
            [
                'name' => '评论',
                'icon' => 'fa-comment',
                'count' => $data['comment_count']
            ],
            [
                'name' => '浏览量',
                'icon' => 'fa-eye',
                'count' => $data['view_count']
            ],
        ];
    }

    public static function userCount(int $user): array {
        return [
            [
                'name' => '博文数量',
                'count' => BlogModel::where('user_id', $user)->count(),
                'unit' => '篇',
            ],
            [
                'name' => '博文评论',
                'count' => CommentModel::query()->where('user_id', $user)->count(),
                'unit' => '条'
            ]
        ];
    }
}
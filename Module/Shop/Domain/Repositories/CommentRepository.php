<?php
namespace Module\Shop\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\CommentModel;

class CommentRepository {

    public static function getList(int $item_id, string $keywords = '', int $item_type = 0) {
        return CommentModel::with('user', 'images')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'content');
            })
            ->where('item_type', $item_type)
            ->where('item_id', $item_id)
            ->orderBy('id', 'desc')->page();
    }

    public static function create(array $data) {
        $data['user_id'] = auth()->id();
        return CommentModel::createOrThrow($data);
    }
    /**
     * 获取评论的统计信息
     * @param $item_id
     * @param int $item_type
     * @return array
     */
    public static function count(int $item_id, int $item_type = 0) {
        $data = CommentModel::where('item_type', $item_type)->where('item_id', $item_id)
            ->groupBy('`rank`')->asArray()
            ->get('`rank`,COUNT(*) AS `count`');
        $args = [
            'total' => 0,
            'good' => 0,
            'middle' => 0,
            'bad' => 0
        ];
        $total = 0;
        foreach ($data as $item) {
            $total += $item['count'] * $item['rank'];
            $args['total'] += $item['count'];
            if ($item['rank'] > 7) {
                $args['good'] += $item['count'];
                continue;
            }
            if ($item['rank'] < 3) {
                $args['bad'] += $item['count'];
                continue;
            }
            $args['middle'] += $item['count'];
        }
        $args['avg'] = $args['total'] > 0 ? round($total / $args['total'], 1) : 10;
        $args['favorable_rate'] = $args['total'] > 0 ?
            ceil($args['good'] * 100 / $args['total']) : 100;
        $args['tags'] = [
            [
                'label' => '好评',
                'count' => $args['good']
            ],
            [
                'label' => '一般',
                'count' => $args['middle']
            ],
            [
                'label' => '差评',
                'count' => $args['bad']
            ],
        ];
        $args['tags'] = array_filter($args['tags'], function ($item) {
            return $item['count'] > 0;
        });
        return $args;
    }

    public static function recommend(int $limit = 6) {
        return CommentModel::with('goods', 'user', 'images')->where('item_type', 0)->limit($limit)->get();
    }
}
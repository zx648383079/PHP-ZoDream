<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CommentModel;

class CommentController extends Controller {

    public function indexAction($item_id, $item_type = 0) {
        $data = CommentModel::with('user', 'images')
            ->where('item_type', $item_type)
            ->where('item_id', $item_id)
            ->orderBy('id', 'desc')->page();
        return $this->renderPage($data);
    }

    public function createAction() {
        $data = app('request')->validate([
            'item_type' => 'int:0,99',
            'item_id' => 'required|int',
            'title' => 'required|string:0,255',
            'content' => 'required|string:0,255',
            'rank' => 'int:0,99',
            'images' => ''
        ]);
        $data['user_id'] = auth()->id();
        $comment = CommentModel::create($data);
        if (empty($comment)) {
            return $this->renderFailure('');
        }
        return $this->render($comment);
    }

    public function countAction($item_id, $item_type = 0) {
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
        $args['comments'] = CommentModel::with('user', 'images')->where('item_type', $item_type)->where('item_id', $item_id)
            ->orderBy('id', 'desc')->limit(2)->get();
        return $this->render($args);
    }
}
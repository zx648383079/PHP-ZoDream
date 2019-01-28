<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\CommentModel;

class CommentController extends Controller {

    public function indexAction($item_id, $item_type = 0) {
        $data = CommentModel::where('item_type', $item_type)->where('item_id', $item_id)
            ->orderBy('id', 'desc')->page();
        return $this->render($data);
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
        return $this->render([
           'total' => 0,
           'good' => 0,
           'middle' => 0,
           'bad' => 0
        ]);
    }
}
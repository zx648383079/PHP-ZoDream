<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsIssueModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Repositories\CommentRepository;

class GoodsController extends Controller {

    public function indexAction($id) {
        if (request()->isMobile()) {
            return $this->redirect(['./mobile/goods', 'id' => $id]);
        }
        $goods = GoodsModel::find($id);
        if (empty($goods)) {
            return $this->redirect('./');
        }
        $gallery_list = GoodsGalleryModel::where('goods_id', $id)->all();
        return $this->sendWithShare()->show(compact('goods', 'gallery_list'));
    }

    public function recommendAction($id) {
        $goods_list = GoodsSimpleModel::limit(7)->all();
        return $this->renderData(compact('goods_list'));
    }

    public function hotAction($id) {
        $goods_list = GoodsSimpleModel::limit(7)->all();
        return $this->renderData(compact('goods_list'));
    }

    public function commentAction($id, $page = -1) {
        $this->layout = false;
        $comment_list = CommentModel::with('images', 'user')->where('item_type', 0)
            ->where('item_id', $id)->page();
        if ($page > 0) {
            return $this->show('commentPage', compact('comment_list'));
        }
        $comment_count = CommentRepository::count($id, 0);
        return $this->show(compact('comment_list', 'comment_count'));
    }

    public function issueAction($id) {
        $this->layout = false;
        $issue_list = GoodsIssueModel::whereIn('goods_id', [$id, 0])->all();
        return $this->show(compact('issue_list'));
    }
}
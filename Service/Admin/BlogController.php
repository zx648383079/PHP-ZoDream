<?php
namespace Service\Admin;

/**
 * 博客
 */
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TermModel;
use Zodream\Domain\Access\Auth;
use Zodream\Domain\Html\Page;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Routing\Url;

class BlogController extends Controller {

	public function indexAction() {
		return $this->show();
	}

	public function listAction($search = null) {
        $where = array();
        if (!empty($search)) {
            $args = explode(' ', $search);
            foreach ($args as $item) {
                $where[] = "b.title like '%{$item}%'";
            }
        }
        $page = new Page(BlogModel::where($where));
        $page->setPage(BlogModel::alias('b')
            ->load(array(
                'left' => array(
                    'user u',
                    'b.user_id = u.id',
                    'term t',
                    'b.term_id = t.id'
                ),
                'where' => $where,
                'order' => 'b.create_at desc',
                'select' => array(
                    'id' => 'b.id',
                    'title' => 'b.title',
                    'user' => 'u.name',
                    'term' => 't.name',
                    'comment_count' => 'b.comment_count',
                    'create_at' => 'b.create_at',
                )
            ))->asArray());
        return $this->jsonSuccess($page);
    }

    public function detailAction($id = null) {
	    $blog = BlogModel::findOrNew($id);
	    $term_list = TermModel::all();
	    return $this->show(compact('blog', 'term_list'));
    }

    public function updateAction() {
	    $blog = BlogModel::findOrNew(intval(Request::post('id')));
	    $blog->user_id = Auth::id();
	    if ($blog->load() && $blog->save()) {
	        return $this->jsonSuccess([
	            'url' => (string)Url::to(['blog'])
            ], '保存成功！');
        }
        return $this->jsonFailure($blog->getFirstError());
    }

    public function termAction() {
        $term_list = TermModel::all();
        return $this->show(compact('term_list'));
    }

    public function UpdateTermAction() {
        $term = TermModel::findOrNew(intval(Request::post('id')));
        if ($term->load() && $term->save()) {
            return $this->jsonSuccess([
                'refresh' => true
            ], '保存成功！');
        }
        return $this->jsonFailure($term->getFirstError());
    }


    public function deleteAction($id) {
	    BlogModel::where(['id' => ['in', $id]]);
	    return $this->jsonSuccess();
    }

    public function settingAction() {
	    return $this->show();
    }
}
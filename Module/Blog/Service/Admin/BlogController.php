<?php
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Events\BlogUpdate;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\TagRepository;
use Zodream\Infrastructure\Http\Request;

class BlogController extends Controller {

    public function indexAction($keywords = '', $term_id = 0, $type = 0) {
        $blog_list = BlogPageModel::with('term')
            ->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                BlogModel::searchWhere($query, 'title');
            })->when(!empty($term_id), function ($query) use ($term_id) {
                $query->where('term_id', intval($term_id));
            })->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })->orderBy('id', 'desc')->page();
        $term_list = TermModel::select('id', 'name')->all();
        return $this->show(compact('blog_list', 'term_list', 'keywords', 'term_id', 'type'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id, $language = null) {
        $model = BlogModel::getOrNew($id, $language);
        if (empty($model) || (!$model->isNewRecord && $model->user_id != auth()->id())) {
            return $this->redirectWithMessage($this->getUrl('blog'), '博客不存在！');
        }
        $term_list = TermModel::select('id', 'name')->all();
        $tags = $model->isNewRecord ? [] : TagRepository::getTags($model->id);
        $metaItems = BlogMetaModel::getMetaWithDefault($id);
        return $this->show(compact('model', 'term_list', 'tags', 'metaItems'));
    }

    public function saveAction(Request $request, $id = 0) {
        try {
            $data = $request->get();
            $data['tags'] = $request->get('tag', []);
            $data = array_merge($request->get('meta', []), $data);
            BlogRepository::save($data, $id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess([
            'url' => $id < 1 ? $this->getUrl('blog') : -1
        ]);
    }

    public function deleteAction($id) {
        try {
            BlogRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}
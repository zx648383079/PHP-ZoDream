<?php
namespace Module\Blog\Service\Admin;

use Domain\Model\SearchModel;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;
use Module\Blog\Domain\Model\TagRelationshipModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Events\BlogUpdate;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\TagRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class BlogController extends Controller {

    public function indexAction(string $keywords = '', int $term_id = 0, int $type = 0) {
        $blog_list = BlogRepository::getSelfList($keywords, $term_id, $type);
        $term_list = TermModel::select('id', 'name')->all();
        return $this->show(compact('blog_list', 'term_list', 'keywords', 'term_id', 'type'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id, string $language = '') {
        $model = BlogModel::getOrNew($id, $language);
        if (empty($model) || (!$model->isNewRecord && $model->user_id != auth()->id())) {
            return $this->redirectWithMessage($this->getUrl('blog'), '博客不存在！');
        }
        $term_list = TermModel::select('id', 'name')->all();
        $tags = $model->isNewRecord ? [] : TagRepository::getTags($model->id);
        $metaItems = BlogMetaModel::getMetaWithDefault($id);
        return $this->show('edit', compact('model', 'term_list', 'tags', 'metaItems'));
    }

    public function saveAction(Request $request, int $id = 0) {
        try {
            $data = $request->get();
            $data['tags'] = $request->get('tag', []);
            $data = array_merge($request->get('meta', []), $data);
            BlogRepository::save($data, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $id < 1 ? $this->getUrl('blog') : -1
        ]);
    }

    public function deleteAction(int $id) {
        try {
            BlogRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}
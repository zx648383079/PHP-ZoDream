<?php
declare(strict_types=1);
namespace Module\Blog\Service\Admin;

use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\TermModel;
use Module\Blog\Domain\Repositories\PublishRepository;
use Module\Blog\Domain\Repositories\TagRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class BlogController extends Controller {

    public function indexAction(string $keywords = '', int $term_id = 0, int $type = 0) {
        $blog_list = PublishRepository::getList($keywords, $term_id, 0, $type);
        $term_list = TermModel::select('id', 'name')->all();
        return $this->show(compact('blog_list', 'term_list', 'keywords', 'term_id', 'type'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id = 0, string $language = '') {
        try {
            $model = PublishRepository::getOrNew($id, $language);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl('blog'), $ex->getMessage());
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
            PublishRepository::save($data, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $id < 1 ? $this->getUrl('blog') : -1
        ]);
    }

    public function deleteAction(int $id) {
        try {
            PublishRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}
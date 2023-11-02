<?php
namespace Module\Exam\Service\Admin;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Repositories\CourseRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CourseController extends Controller {

    public function indexAction() {
        $items = CourseRepository::all(true);
        return $this->show(compact('items'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = CourseModel::findOrNew($id);
        $cat_list = CourseRepository::all();
        if (!empty($id)) {
            $excludes = [$id];
            $cat_list = array_filter($cat_list, function ($item) use (&$excludes) {
                if (in_array($item['id'], $excludes)) {
                    return false;
                }
                if (in_array($item['parent_id'], $excludes)) {
                    $excludes[] = $item['id'];
                    return false;
                }
                return true;
            });
        }
        return $this->show('edit', compact('model', 'cat_list'));
    }

    public function saveAction(Input $input) {
        try {
            CourseRepository::save($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('course')
        ]);
    }

    public function deleteAction(int $id) {
        CourseRepository::remove($id);
        return $this->renderData([
            'url' => $this->getUrl('course')
        ]);
    }
}
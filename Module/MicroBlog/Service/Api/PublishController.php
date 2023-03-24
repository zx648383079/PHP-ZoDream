<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;


use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PublishController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(string $keywords = '', ) {
        return $this->renderPage(
            MicroRepository::getList('new', $keywords, 0, auth()->id())
        );
    }


    public function createAction(Input $request) {
        if (!MicroRepository::canPublish()) {
            return $this->renderFailure('发送过于频繁！');
        }
        try {
            $model = MicroRepository::create($request->get('content'), $request->get('file', []));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            MicroRepository::removeSelf($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}
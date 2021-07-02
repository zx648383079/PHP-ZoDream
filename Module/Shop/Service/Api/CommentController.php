<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Repositories\CommentRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CommentController extends Controller {

    public function indexAction(int $item_id, string $keywords = '', int $item_type = 0) {
        return $this->renderPage(
            CommentRepository::getList($item_id, $keywords, $item_type),
        );
    }

    public function createAction(Input $input) {
        try {
            $data = $input->validate([
                'item_type' => 'int:0,99',
                'item_id' => 'required|int',
                'title' => 'required|string:0,255',
                'content' => 'required|string:0,255',
                'rank' => 'int:0,99',
                'images' => ''
            ]);
            return $this->render(
                CommentRepository::create($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function countAction(int $item_id, int $item_type = 0, bool $recommend = false) {
        $args = CommentRepository::count($item_id, $item_type);
        if ($recommend) {
            $args['comments'] = CommentRepository::recommend(2);
        }
        return $this->render($args);
    }

    public function recommendAction() {
        return $this->renderData(CommentRepository::recommend());
    }
}
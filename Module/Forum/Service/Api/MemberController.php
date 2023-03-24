<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api;


use Module\Forum\Domain\Repositories\ThreadRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class MemberController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function threadAction(string $keywords = '') {
        return $this->renderPage(
            ThreadRepository::selfList($keywords)
        );
    }

    public function threadDeleteAction(int $id) {
        try {
            ThreadRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function postAction(string $keywords = '') {
        return $this->renderPage(
            ThreadRepository::selfPostList($keywords)
        );
    }

    public function postDeleteAction(int $id) {
        try {
            ThreadRepository::removePost($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}
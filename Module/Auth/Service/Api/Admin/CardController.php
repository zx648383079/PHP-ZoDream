<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\CardRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CardController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(CardRepository::getList($keywords));
    }

    public function saveAction(Input $request) {
        try {
            $model = CardRepository::save($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,32',
                'icon' => 'required|string:0,255',
                'configure' => 'string:0,200',
                'status' => 'int:0,127',
            ]));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        CardRepository::remove($id);
        return $this->renderData(true);
    }

    public function userAction(int $user) {
        return $this->renderPage(CardRepository::userCardList($user));
    }

    public function userUpdateAction(Input $request) {
        try {
            CardRepository::userUpdate($request->validate([
                'card_id' => 'required|int',
                'user_id' => 'required|int',
                'expired_at' => 'required|string',
            ]));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function searchAction(string $keywords = '',
                                 int|array $id = 0) {
        return $this->renderPage(CardRepository::search($keywords, $id));
    }

}
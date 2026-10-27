<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Advanced\CardRepository;
use Module\Auth\Domain\Advanced\RankRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CardController extends Controller {

    public function rules() {
        if (!AUTH_ADVANCED) {
            return ['*' => '!'];
        }
        return parent::rules();
    }

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

    public function rankAction(string $keywords = '') {
        return $this->renderPage(RankRepository::getList($keywords));
    }

    public function rankSaveAction(Input $request) {
        try {
            $model = RankRepository::save($request->validate([
                'id' => 'int',
                'level' => 'required|int',
                'name' => 'required|string:0,32',
                'icon' => 'string:0,255',
                'configure' => 'string:0,200',
            ]));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function rankDeleteAction(int $id) {
        RankRepository::remove($id);
        return $this->renderData(true);
    }

}
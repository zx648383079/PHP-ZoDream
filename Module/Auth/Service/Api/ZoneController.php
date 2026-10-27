<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Advanced\ZoneRepository;

final class ZoneController extends Controller {
    public function rules() {
        if (!AUTH_ADVANCED) {
            return ['*' => '!'];
        }
        return [
            '*' => '@',
        ];
    }

    public function indexAction() {
        return $this->render(ZoneRepository::userZone());
    }

    public function saveAction(array|int $id) {
        try {
            ZoneRepository::save(auth()->id(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}
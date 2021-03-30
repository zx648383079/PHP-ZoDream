<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Repositories\AgreementRepository;

class AgreementController extends Controller {

    public function indexAction(string $name) {
        try {
            return $this->render(AgreementRepository::getByName($name));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}
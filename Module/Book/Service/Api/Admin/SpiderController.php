<?php
declare(strict_types=1);
namespace Module\Book\Service\Api\Admin;

use Module\Book\Domain\Repositories\SpiderRepository;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Error\Exception;

final class SpiderController extends Controller {

    public function indexAction(string $keywords) {
        try {
            return $this->renderData(SpiderRepository::search($keywords));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function asyncAction(Input $input) {
        try {
            return $this->renderData(
                SpiderRepository::async($input->post())
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}
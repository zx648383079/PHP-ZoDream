<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Module\Catering\Domain\Repositories\OrderRepository;

class OrderController extends Controller {

	public function indexAction(string $keywords = '', int $user = 0) {
        return $this->renderPage(OrderRepository::merchantList($keywords, $user));
	}
}
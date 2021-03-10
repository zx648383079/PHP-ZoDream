<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api;

use Module\Forum\Domain\Repositories\EmojiRepository;

class EmojiController extends Controller {

    public function indexAction() {
        return $this->renderData(
            EmojiRepository::all()
        );
    }

}
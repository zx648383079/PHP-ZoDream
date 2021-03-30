<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Repositories\EmojiRepository;

class EmojiController extends Controller {

    public function indexAction() {
        return $this->renderData(
            EmojiRepository::all()
        );
    }

}
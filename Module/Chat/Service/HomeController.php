<?php
declare(strict_types=1);
namespace Module\Chat\Service;

use Zodream\Disk\File;

class HomeController extends Controller {

    protected File|string $layout = 'main';

    public function indexAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }

}
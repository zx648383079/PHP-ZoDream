<?php
declare(strict_types=1);
namespace Module\Disk\Service\Api\Admin;

use Module\Disk\Domain\Repositories\ClientRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class ClientController extends Controller {

    public function indexAction() {
        return $this->renderData([]);
    }

    public function fileAction() {
        return $this->renderData([]);
    }

    public function linkServerAction(Input $input) {
        try {
            $data = $input->validate([
                'linked' => 'bool',
                'server_url' => 'required',
                'upload_url' => '',
                'download_url' => '',
                'ping_url' => '',
            ]);
            return $this->render(ClientRepository::link($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}
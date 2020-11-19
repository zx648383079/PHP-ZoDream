<?php
namespace Module\Disk\Service;

use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Service\Factory;

/**
 * 下载
 * Class DownloadController
 * @package Module\Disk\Service
 */
class DownloadController extends Controller {
    
    public function indexAction($id) {
        $response = Factory::response();
        try {
            $data = DiskRepository::driver()->file($id);
        } catch (\Exception $ex) {
            $response->header->setContentDisposition('error.txt');
            return $response->custom($ex->getMessage(), 'txt');
        }
        $data['path']->setExtension($data['extension'])
            ->setName($data['name']);
        return $response->file($data['path']);
    }
}
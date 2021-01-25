<?php
namespace Module\Disk\Service\Api;

use Exception;
use Module\Disk\Domain\Repositories\DiskRepository;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Http\Output;

class HomeController extends Controller {

    public function indexAction($id, $path = '') {
        return $this->renderPage(DiskRepository::driver()->catalog($id, $path));
    }

    /**
     * 进行网址访问许可
     * @param $url
     * @return Output
     * @throws Exception
     */
    public function allowAction($url) {
        if (empty($url)) {
            return $this->renderFailure('网址必传');
        }
        try {
            return $this->renderData(
                DiskRepository::allowUrl($url)
            );
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function fileAction($id) {
        try {
            $disk = DiskRepository::driver()->file($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        foreach ([
            'thumb', 'url', 'subtitles'
                 ] as $key) {
            if (!isset($disk[$key]) || empty($disk[$key])) {
                continue;
            }
            if ($key !== 'subtitles') {
                $disk[$key] = DiskRepository::allowUrl($disk[$key]);
                continue;
            }
            $disk[$key] = array_map(function ($item) {
                $item['url'] = DiskRepository::allowUrl(url('./file/subtitles', ['id' => $item['id']]));
                return $item;
            }, $disk[$key]);
        }
        unset($disk['path']);
        return $this->render($disk);
    }
}
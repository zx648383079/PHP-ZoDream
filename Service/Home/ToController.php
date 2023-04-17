<?php
declare(strict_types=1);
namespace Service\Home;

use Module\Counter\Domain\Events\JumpOut;
use Zodream\Disk\File;
use Zodream\Helpers\Html;

class ToController extends Controller {
    public File|string $layout = '';

    /**
     * 跳出链接
     * @path /to
     * @method get
     * @param string $url
     * @throws \Exception
     */
    public function indexAction(string $url = '') {
        $autoJump = true;
        if (!empty($url)) {
            $url = base64_decode($url.'=');
            $autoJump = $this->checkUrl($url);
            event(JumpOut::create($url));
        }
        if (empty($url)) {
            $url = url('/');
        }
        $url = Html::text($url);
        return $this->show(compact('url', 'autoJump'));
    }

    private function checkUrl(string $url): bool {
        $query = parse_url($url, PHP_URL_QUERY);
        if (empty($query)) {
            return true;
        }
        $data = [];
        parse_str($query, $data);
        foreach ($data as $key => $_) {
            $key = strtolower($key);
            if (str_contains($key, 'url') || str_contains($key, 'uri')) {
                return false;
            }
        }
        return true;
    }

    public static function to(string $url) {
        return url('/to', ['url' => rtrim(base64_encode($url), '=')]);
    }
}
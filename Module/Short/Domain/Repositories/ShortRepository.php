<?php
namespace Module\Short\Domain\Repositories;

use Exception;
use Module\Short\Domain\Model\ShortLogModel;
use Module\Short\Domain\Model\ShortUrlModel;
use Zodream\Helpers\Str;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Http\Request;

class ShortRepository {

    /**
     * 系统内部创建
     * @param string $path
     * @param $parameters
     * @return string
     */
    public static function systemCreate(string $path, $parameters): string {
        try {
            $url = url($path, $parameters, true, false);
            return static::createShort($url, true)->complete_short_url;
        } catch (Exception $ex) {
            return $path;
        }
    }

    /**
     * 前台申请
     * @param string $source_url
     * @return false|ShortUrlModel
     * @throws Exception
     */
    public static function create(string $source_url) {
        if (empty($source_url)) {
            throw new Exception('请输入长网址');
        }
        $uri = new Uri($source_url);
        if (empty($uri->getHost())) {
            throw new Exception('请输入完整网址');
        }
        // TODO 域名黑名单验证
        return static::createShort($source_url);
    }

    /**
     * @param $source_url
     * @param bool $is_system
     * @return false|ShortUrlModel
     * @throws Exception
     */
    private static function createShort($source_url, $is_system = false) {
        /** @var ShortUrlModel $model */
        $model = ShortUrlModel::where('source_url', $source_url)->first();
        if (!empty($model)) {
            if ($model->expired_at > 0 && $model->expired_at < time()) {
                $model->expired_at = time() + 86400 * 30;
                $model->save();
            }
            return $model;
        }
        $short_url = static::generateShort(Str::randomInt(4, 8));
        $model = ShortUrlModel::create([
            'user_id' => auth()->id(),
            'short_url' => $short_url,
            'source_url' => $source_url,
            'click_count' => 0,
            'status' => 1,
            'is_system' => $is_system,
            'expired_at' => 0,
        ]);
        if (empty($model)) {
            throw new Exception('生成失败');
        }
        return $model;
    }

    /**
     * @param string $short
     * @param Request $request
     * @return ShortUrlModel
     * @throws Exception
     */
    public static function click(string $short, Request $request) {
        /** @var ShortUrlModel $model */
        $model = ShortUrlModel::where('short_url', $short)->first();
        if (empty($model) ||  $model->status != 1) {
            throw new Exception('链接不可用');
        }
        if ($model->expired_at > 0 && $model->expired_at < time()) {
            throw new Exception('链接不可用');
        }
        $model->click_count ++;
        $model->save();
        ShortLogModel::create([
            'short_id' => $model->id,
            'ip' => $request->ip()
        ]);
        return $model;
    }

    public static function generateShort(int $length = 4): string {
        while (true) {
            $short = Str::quickRandom($length);
            if (static::check($short)) {
                return $short;
            }
        }
    }

    public static function check(string $short): bool {
        return ShortUrlModel::query()->where('short_url', $short)->count() < 1;
    }
}
<?php
declare(strict_types=1);
namespace Module\Short\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Short\Domain\Model\ShortLogModel;
use Module\Short\Domain\Model\ShortUrlModel;
use Zodream\Helpers\Str;
use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ShortRepository {

    /**
     * 系统内部创建
     * @param string $path
     * @param array $parameters
     * @return string
     */
    public static function systemCreate(string $path, array $parameters): string {
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
     * @param string $source_url
     * @param bool $is_system
     * @return false|ShortUrlModel
     * @throws Exception
     */
    private static function createShort(string $source_url, bool $is_system = false) {
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
            'title' => 'unknown',
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

    public static function getManageList(
        string $keywords = '',
        int $user = 0) {
        return ShortUrlModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title', 'source_url']);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function getList(
        string $keywords = '',
        int $user = 0, int $id = 0) {
        return ShortUrlModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query,  ['title', 'source_url']);
            })
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')
            ->page();
    }

    public static function save(array $data, int $userId = 0) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if ($id < 1) {
            $model = ShortUrlModel::where('source_url', $data['source_url'])
                ->where('user_id', auth()->id())->first();
            if ($model) {
                return $model;
            }
            if (empty($data['title'])) {
                $data['title'] = 'unknown';
            }
        }
        $model = ShortUrlModel::findOrNew($id);
        if ($id > 0 && $userId > 0 && $model->user_id != $userId) {
            throw new Exception('url error');
        }
        $model->load($data);
        if ($userId > 0) {
            $model->user_id = $userId;
        }
        if ($model->isNewRecord) {
            $model->short_url = self::generateShort();
        }
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function saveSelf(array $data) {
        return static::save($data, auth()->id());
    }

    public static function remove(int $id) {
        ShortUrlModel::where('id', $id)->delete();
    }

    public static function removeSelf(int $id) {
        ShortUrlModel::where('id', $id)->where('user_id', auth()->id())->delete();
    }
}
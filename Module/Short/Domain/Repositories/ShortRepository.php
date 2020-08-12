<?php
namespace Module\Short\Domain\Repositories;

use Module\Short\Domain\Model\ShortLogModel;
use Module\Short\Domain\Model\ShortUrlModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Http\Request;

class ShortRepository {

    public static function create($source) {
    }

    /**
     * @param string $short
     * @param Request $request
     * @return ShortUrlModel
     * @throws \Exception
     */
    public static function click(string $short, Request $request) {
        /** @var ShortUrlModel $model */
        $model = ShortUrlModel::where('short_url', $short)->first();
        if (empty($model) ||  $model->status != 1) {
            throw new \Exception('链接不可用');
        }
        if ($model->expired_at > 0 && $model->expired_at < time()) {
            throw new \Exception('链接不可用');
        }
        $model->click_log ++;
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
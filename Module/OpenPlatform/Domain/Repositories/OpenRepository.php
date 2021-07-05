<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Domain\Repositories;

use Exception;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Helpers\Time;

class OpenRepository {

    /**
     * 前台保存应用
     * @param array $data
     * @return PlatformModel
     * @throws Exception
     */
    public static function savePlatform(array $data) {
        $id = isset($data['id']) ? intval($data['id']) : 0;
        unset($data['appid']);
        unset($data['secret']);
        unset($data['rules']);
        unset($data['status']);
        if ($id > 0) {
            $model = PlatformModel::where('user_id', auth()->id())
                ->where('id', $id)->first();
        } else {
            $model = new PlatformModel();
            $model->user_id = auth()->id();
            $model->generateNewId();
            $model->status = PlatformModel::STATUS_WAITING;
        }
        if (empty($model)) {
            throw new Exception('应用不存在');
        }
        if (!$model->load($data) || !$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    /**
     * 创建token
     * @param int $platform_id
     * @return UserTokenModel
     * @throws Exception
     */
    public static function createToken(int $platform_id, string $expired_at = '') {
        if ($platform_id < 0) {
            throw new Exception('请选择应用');
        }
        $platform = PlatformModel::where('id', $platform_id)->where('allow_self', 1)->where('status', 1)->first();
        if (!$platform) {
            throw new Exception('请选择应用');
        }
        $model = UserTokenModel::create([
            'user_id' => auth()->id(),
            'platform_id' => $platform->id,
            'token' => md5(sprintf('%s:%s', auth()->id(), Time::millisecond())),
            'expired_at' => empty($expired_at) ? time() + 86400 : strtotime($expired_at),
            'is_self' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        if (empty($model)) {
            throw new Exception('创建失败');
        }
        return $model;
    }

    /**
     * 分享接口验证网址
     * @param string $appId
     * @param string $url
     * @return PlatformModel
     * @throws Exception
     */
    public static function checkUrl(string $appId, string $url) {
        if (empty($url)) {
            throw new Exception('网址不能为空');
        }
        if (empty($appId)) {
            throw new Exception('应用无效');
        }
        $model = PlatformModel::findByAppId($appId);
        if (empty($model) || empty($model->domain)) {
            throw new Exception('应用无效');
        }
        if ($model->domain === '*') {
            return $model;
        }
        $host = parse_url($url, PHP_URL_HOST);
        if ($host !== $model->domain) {
            throw new Exception('应用域名不匹配');
        }
        return $model;
    }
}
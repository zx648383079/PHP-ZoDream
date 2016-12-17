<?php
namespace Domain\Model;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/18
 * Time: 21:41
 */
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Http\Requests\BaseRequest;

abstract class Model extends \Zodream\Domain\Model\Model {

    /**
     * 自动完成更新或插入 并添加更新时间、用户id、ip、插入时间
     * @return bool|int
     */
    public function fill() {
        $args = func_num_args() > 0 ? func_get_arg(0) : Request::post();
        if ($args instanceof BaseRequest) {
            $args = $args->get();
        }
        if (!array_key_exists('update_at', $args)) {
            $args['update_at'] = time();
        }
        if (isset($args['id']) && !empty($args['id'])) {
            return parent::fill($args, intval($args['id']));
        }
        if (!array_key_exists('user_id', $args) && !Auth::guest()) {
            $args['user_id'] = Auth::user()['id'];
        }
        if (!array_key_exists('ip', $args)) {
            $args['ip'] = Request::ip();
        }
        if (!array_key_exists('create_at', $args)) {
            $args['create_at'] = $args['update_at'];
        }
        return parent::fill($args);
    }
}
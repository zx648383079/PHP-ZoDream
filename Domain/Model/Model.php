<?php
namespace Domain\Model;
use Zodream\Domain\Authentication\Auth;
use Zodream\Infrastructure\Request;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/18
 * Time: 21:41
 */
abstract class Model extends \Zodream\Domain\Model {
    /**
     * 自动完成更新或插入 并添加更新时间、用户id、ip、插入时间
     * @return bool|int
     */
    public function fill() {
        if (func_num_args() === 0) {
            return false;
        }
        $args = func_get_arg(0);
        $args['update_at'] = time();
        if (isset($args['id']) && !empty($args['id'])) {
            return parent::fill($args, intval($args['id']));
        }
        if (!Auth::guest()) {
            $args['user_id'] = Auth::user()['id'];
        }
        $args['ip'] = Request::ip();
        $args['create_at'] = $args['update_at'];
        return parent::fill($args);
    }
}
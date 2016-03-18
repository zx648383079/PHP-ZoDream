<?php
namespace Domain\Model;
use Zodream\Domain\Authentication\Auth;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/18
 * Time: 21:41
 */
abstract class Model extends \Zodream\Domain\Model {
    public function fill() {
        $args = func_get_arg(0);
        if (isset($args['id']) && !empty($args['id'])) {
            $args['update_at'] = time();
            return parent::fill($args, $args['id']);
        }
        if (!Auth::guest()) {
            $args['user_id'] = Auth::user()['id'];
        }
        $args['create_at'] = time();
        return parent::fill($args);
    }
}
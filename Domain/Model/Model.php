<?php
namespace Domain\Model;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/3/18
 * Time: 21:41
 */
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Html\Page;
use Zodream\Infrastructure\Request;

abstract class Model extends \Zodream\Domain\Model {
    /**
     * 自动完成更新或插入 并添加更新时间、用户id、ip、插入时间
     * @return bool|int
     */
    public function fill() {
        $args = func_num_args() > 0 ? func_get_arg(0) : Request::post();
        if ($args instanceof Request\BaseRequest) {
            $args = $args->get();
        }
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

    /**
     * 获取简单的分页
     * @param string|array $sql from后的 语句
     * @param string $field
     * @param string|array $countSql count的 sql语句， 为空则使用$sql
     * @return Page
     */
    public function getPage($sql = null, $field = '*', $countSql = null) {
        $sql = $this->getQuery($sql);
        $page = new Page($this->getCount(
            is_null($countSql) ? $sql : $this->getQuery($countSql),
            '*'
        ));
        $page->setPage($this->findAll($sql .' LIMIT '.$page->getLimit(), $this->getField($field)));
        return $page;
    }

    /**
     * 获取总数
     * @param string $sql
     * @param string $field
     * @return string
     */
    public function getCount($sql, $field = '*') {
        return $this->scalar($sql.' LIMIT 1', "COUNT({$field}) AS count");
    }
}
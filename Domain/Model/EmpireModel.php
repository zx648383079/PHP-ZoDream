<?php
namespace Domain\Model;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/4/1
 * Time: 21:15
 */
use Zodream\Domain\Authentication\Auth;
use Zodream\Domain\Filter\DataFilter;
use Zodream\Domain\Html\Page;
use Zodream\Domain\Routing\UrlGenerator;
use Zodream\Infrastructure\Request;

class EmpireModel extends Model {

    /**
     * 保存表单数据
     * @param array $rules 验证规则
     * @param array $data 如果为空则自动获取post数据
     * @return bool|int false 表示验证失败 int 表示 id
     */
    public function save($rules = array(), $data = array()) {
        if (!empty($rules)) {
            $this->fillAble = array_keys($rules);
        }
        if (empty($data)) {
            $data = Request::post();
        }
        if (!empty($rules) && !DataFilter::validate($data, $rules)) {
            return false;
        }
        return $this->fill($data);
    }

    /**
     * 获取简单的分页
     * @param string|array $sql from后的 语句
     * @param string $field
     * @param string|array $countSql count的 sql语句， 为空则使用$sql
     * @return Page
     */
    public function getPage($sql = null, $field = '*', $countSql = null) {
        $sql = $this->getBySort($sql);
        $page = new Page($this->getCount(
            empty($countSql) ? $sql : $this->getBySort($countSql), 
            '*'
        ));
        $page->setPage($this->find($sql .' LIMIT '.$page->getLimit(), $field));
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

    public function getMenu($type) {
        return $this->setTable('enewsmenuclass c')->find(array(
            'right' => array(
                'enewsmenu m',
                'm.classid = c.classid'
            ),
            'where' => array(
                'c.classtype='.intval($type),
                "(groupids='' or groupids like '%1%')"
            ),
            //'group' => 'c.classname',
            'order' => 'c.classname,m.myorder,m.menuid'
        ), 'c.classname as class,m.menuid as id,m.menuname as name,m.menuurl as url,m.addhash as hash');
    }

    public function getRoles($id) {
        return $this->findByHelper(array(
            'select' => 'a.id as id,a.name as name',
            'from' => 'role_user ru',
            'left' => array(
                'authorization_role ar',
                'ru.role_id = ar.role_id'
            ),
            '`left' => array(
                'authorization a',
                'a.id = ar.authorization_id'
            ),
            'where' => 'ru.user_id = '.intval($id)
        ));
    }

    /**
     * 根据角色id 获取权限id 列表
     * @param integer|string $id
     * @return array
     */
    public function getAuthByRole($id) {
        $this->setTable('authorization_role');
        $data = $this->find(array(
            'where' => 'role_id = '.intval($id)
        ), 'authorization_id');
        $result = array();
        foreach ($data as $item) {
            $result[] = $item['authorization_id'];
        }
        return $result;
    }

    /**
     * 添加纪录
     * @param $data
     * @param $action
     * @return int
     */
    public function addLog($data, $action) {
        $this->setTable('log');
        return $this->add(array(
            'event' => $action,
            'data' => is_string($data) ? $data : json_encode($data),
            'url' => UrlGenerator::to(),
            'ip' => Request::ip(),
            'create_at' => time(),
            'user' => Auth::guest() ? null : Auth::user()['name']
        ));
    }

    /**
     * 纪录登录记录
     * @param string $user 登录邮箱
     * @param bool $status 成功或失败
     * @param int $mode 页面登录或其他
     * @return int
     */
    public function addLoginLog($user, $status = false, $mode = 1) {
        $this->setTable('login_log');
        return $this->add(array(
            'ip' => Request::ip(),
            'user' => $user,
            'status' => $status,
            'mode' => $mode,
            'create_at' => time()
        ));
    }

    /**
     * @var EmpireModel
     */
    private static $instance;

    /**
     * 查询开始
     * @param null|string $table
     * @return EmpireModel
     */
    public static function query($table = null) {
        if (!self::$instance instanceof Model) {
            self::$instance = new static();
        }
        if (!empty($table)) {
            self::$instance->setTable($table);
        }
        return self::$instance;
    }
}
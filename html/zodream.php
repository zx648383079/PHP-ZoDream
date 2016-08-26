<?php
abstract class BaseEngine {

    protected $driver             = null;

    //存放当前操作的错误信息
    protected $error           = null;

    protected $result;

    protected $version;


    protected $configs = array(
        'host'     => 'localhost',                //服务器
        'port'     => '3306',						//端口
        'database' => 'test',				//数据库
        'user'     => 'root',						//账号
        'password' => '',					//密码
        'prefix'   => '',					//前缀
        'encoding' => 'utf8',					//编码
        'persistent' => false                   //使用持久化连接
    );

    //私有克隆
    protected function __clone() {}

    public function __construct(array $config) {
        $this->configs = array_merge($this->configs, $config);
        $this->connect();
    }

    public function getConfig($key, $default = null) {
        return array_key_exists($key, $this->configs) ? $this->configs[$key] : $default;
    }

    protected abstract function connect();

    public function getDriver() {
        return $this->driver;
    }

    public function getVersion() {
        if (empty($this->version)) {
            $args = $this->select('SELECT VERSION();');
            if (count($args) > 0 && count($args[0]) > 0) {
                $this->version = current($args[0]);
            }
        }
        return $this->version;
    }

    /**
     * 查询
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    public function select($sql, $parameters = array()) {
        return $this->getArray($sql, $parameters);
    }

    /**
     * 插入
     * @param string $sql
     * @param array $parameters
     * @return int id
     */
    public function insert($sql, $parameters = array()) {
        $this->execute($sql, $parameters);
        return $this->lastInsertId();
    }

    /**
     * 修改
     * @param string $sql
     * @param array $parameters
     * @return int 改变的行数
     */
    public function update($sql, $parameters = array()){
        $this->execute($sql, $parameters);
        return $this->rowCount();
    }

    /**
     * 删除
     * @param string $sql
     * @param array $parameters
     * @return int 删除的行数
     */
    public function delete($sql, $parameters = array()) {
        $this->execute($sql, $parameters);
        return $this->rowCount();
    }

    /**
     * 事务开始
     * @return bool
     */
    abstract public function begin();

    /**
     * 执行事务
     * @param array $args
     * @return bool
     */
    public function transaction($args) {
        $this->begin();
        try {
            $this->commit($args);
            return true;
        } catch (Exception $ex) {
            $this->rollBack();
            $this->error = $ex->getMessage();
            return false;
        }
    }

    /**
     * 执行事务
     * @param array $args
     * @return bool
     * @throws \Exception
     */
    abstract public function commit($args = array());

    /**
     * 事务回滚
     * @return bool
     */
    abstract public function rollBack();

    /**
     * 获取最后修改的id
     * @return string
     */
    abstract public function lastInsertId();

    /**
     * 改变的行数
     */
    abstract public function rowCount();

    /**
     * 获取Object结果集
     * @param string $sql
     * @param array $parameters
     * @return mixed
     */
    abstract public function getObject($sql, $parameters = array());

    /**
     * 获取关联数组
     * @param string $sql
     * @param array $parameters
     * @return
     */
    abstract public function getArray($sql, $parameters = array());


    abstract public function execute($sql = null, $parameters = array());



    /**
     * 得到当前执行语句的错误信息
     *
     * @access public
     *
     * @return string 返回错误信息,
     */
    public function getError() {
        return $this->error;
    }

    public function close() {
        $this->driver = null;
    }

}

class ZoDream extends BaseEngine {

    /**
     * 连接数据库
     *
     */
    protected function connect() {
        if (empty($this->configs)) {
            die ('Mysql host is not set');
        }
        if ($this->configs['persistent'] === true) {
            $this->driver = mysql_pconnect(
                $this->configs['host']. ':'. $this->configs['port'],
                $this->configs['user'],
                $this->configs['password']
            ) or die('There was a problem connecting to the database');;
        } else {
            $this->driver = mysql_connect(
                $this->configs['host'] . ':' . $this->configs['port'],
                $this->configs['user'],
                $this->configs['password']
            ) or die('There was a problem connecting to the database');
        }

        mysql_select_db($this->configs['database'], $this->driver)
        or die ("Can't use {$this->configs['database']} : " . mysql_error());
        if (isset($this->configs['encoding'])) {
            mysql_query('SET NAMES '.$this->configs['encoding'], $this->driver);
        }
    }

    public function rowCount() {
        return mysql_affected_rows($this->driver);
    }

    /**
     * 获取Object结果集
     * @param string $sql
     * @param array $parameters
     * @return object
     */
    public function getObject($sql, $parameters = array()) {
        $this->execute($sql);
        $result = array();
        while (!!$objs = mysql_fetch_object($this->result) ) {
            $result[] = $objs;
        }
        return $result;
    }

    /**
     * 获取关联数组
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    public function getArray($sql, $parameters = array()) {
        $this->execute($sql);
        $result = array();
        while (!!$objs = mysql_fetch_assoc($this->result) ) {
            $result[] = $objs;
        }
        return $result;
    }

    /**
     * 返回上一步执行INSERT生成的id
     *
     * @access public
     *
     */
    public function lastInsertId() {
        return mysql_insert_id($this->driver);
    }

    /**
     * 执行SQL语句
     *
     * @access public
     *
     * @param string $sql 多行查询语句
     * @param array $parameters
     * @return null|resource
     */
    public function execute($sql = null, $parameters = array()) {
        if (empty($sql)) {
            return null;
        }
        foreach ($parameters as $key => $item) {
            StringExpand::bindParam($sql, $key + 1, $item, is_numeric($item) ? 'INT' : 'STR');
        }
        $this->result = mysql_query($sql, $this->driver);
        return $this->result;
    }

    /**
     * 关闭和清理
     *
     * @access public
     *
     *
     */
    public function close() {
        if (!empty($this->result) && !is_bool($this->result)) {
            mysql_free_result($this->result);
        }
        mysql_close($this->driver);
        parent::close();
    }

    public function getError() {
        return mysql_error($this->driver);
    }

    public function __destruct() {
        $this->close();
    }

    /**
     * 事务开始
     * @return bool
     */
    public function begin() {
        $arg = mysql_query('START TRANSACTION', $this->driver);
        return empty($arg);
    }

    /**
     * 执行事务
     * @param array $args
     * @return bool
     * @throws \Exception
     */
    public function commit($args = array()) {
        foreach ($args as $item) {
            $res = mysql_query($item, $this->driver);
            if (!$res) {
                throw new Exception('事务执行失败！');
            }
        }
        $arg = mysql_query('COMMIT');
        $result = empty($arg);
        mysql_query('END', $this->driver);
        return $result;
    }

    /**
     * 事务回滚
     * @return bool
     */
    public function rollBack() {
        $arg = mysql_query('ROLLBACK', $this->driver);
        $result = empty($arg);
        mysql_query('END', $this->driver);
        return $result;
    }
}

$qdo = new ZoDream(array(
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'sqlejhuicn',
    'user' => 'ejhuicn',
    'password' => 'q1w2e3r4t5',
    'prefix' => 'ejh_',
    'encoding' => 'utf8'
));

$args = $qdo->getArray('SELECT * FROM ejh_addon18 WHERE aid IN (580, 601, 677, 679, 682, 683, 684, 686, 710, 748, 749, 811, 815, 831, 832, 833, 834, 835, 836, 837, 838, 839)');
$qdo->close();
$file = fopen(dirname(__FILE__).'/zodreamold.sql', 'w');
foreach ($args as $item) {
    $data = array();
    foreach ($item as $key => $value) {
        if ($key == 'aid' || $key == 'content') {
            continue;
        }
        $data[] = "`{$key}` = '{$value}'";
    }
    $sql = "UPDATE `ejh_addon18` SET ".implode(',', $data)." WHERE `aid` = {$item['aid']};\r\n";
    fwrite($file, $sql);
}
fclose($file);
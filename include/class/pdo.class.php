<?php
class PdoClass
{
	//pdo对象  
    private $pdo = null;  
    //用于存放实例化的对象  
    static private $instance = null;  
    //存放表名前缀
    private $prefix = null;
    //存放当前要操作的表
    private $table=null;
    
    //存放当前操作的错误信息
    private $error=null;
       
    //公共静态方法获取实例化的对象  
    static public function getInstance() {  
        if (!(self::$instance instanceof self)) {  
            self::$instance = new self();  
        }  
        return self::$instance;  
    }  
       
    //私有克隆  
    private function __clone() {}  
       
    /**
	 * 公有构造
	 *
	 * @access private
	 *
	 * @param string $table 操作的表.
	 * @param string|array $config_path 数据库的配置信息.
	 * @return 可能会返回False,
	 */
    public function __construct($table,$config_path) {  
        $config=array();
		if(is_array($config_path)){
			$config=$config_path;
		}else{
			$configTem=require($config_path);
		    $config=$configTem['mysql'];
		}
        
        $host = $config['host'];
	    $user = $config['user'];
	    $pwd = $config['password'];
	    $database = $config['database'];
	    $coding = $config['encoding'];
        
	    $this->prefix=$config['prefix'];
        
        $this->table=$this->prefix.$table;
        

        
        try {  
            $this->pdo = new PDO('mysql:host='.$host.';dbname='.$database, $user, $pwd ,
                                 array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES {$coding}"));  
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
        } catch (PDOException $ex) {  
            $this->error=$ex->getMessage();
            return false;
        }  
    }  
       
    /**
	 * 新增记录
	 *
	 * @access public
	 *
	 * @param array $_addData 需要添加的集合
	 * @return 返回影响的行数,
	 */
    public function add(Array $_addData) {  
        $_addFields = array();  
        $_addValues = array();  
        foreach ($_addData as $_key=>$_value) {  
            $_addFields[] = $_key;  
            $_addValues[] = $_value;  
        }  
        $_addFields = implode(',', $_addFields);  
        $_addValues = implode("','", $_addValues);  
        $_sql = "INSERT INTO {$this->table} ($_addFields) VALUES ('$_addValues')";  
        return $this->execute($_sql)->rowCount();  
    }  
       
    /**
	 * 修改记录
	 *
	 * @access public
	 *
	 * @param array $_param 条件
     * @param array $_updateData 需要修改的内容
	 * @return 返回影响的行数,
	 */
    public function update(Array $_param, Array $_updateData) {  
        $_where = $_setData = '';  
        foreach ($_param as $_key=>$_value) {  
            $_where .= $_value.' AND ';  
        }  
        $_where = 'WHERE '.substr($_where, 0, -4);  
        foreach ($_updateData as $_key=>$_value) {  
            if (is_array($_value)) {  
                $_setData .= "$_key=$_value[0],";  
            } else {  
                $_setData .= "$_key='$_value',";  
            }  
        }  
        $_setData = substr($_setData, 0, -1);  
        $_sql = "UPDATE {$this->table} SET $_setData $_where";  
        return $this->execute($_sql)->rowCount();  
    }  
       
    /**
	 * 验证一条数据
	 *
	 * @access public
	 *
	 * @param array $_param 条件
	 * @return 返回影响的行数,
	 */
    public function isOne(Array $_param) {  
        $_where = '';  
        foreach ($_param as $_key=>$_value) {  
            $_where .=$_value.' AND ';  
        }  
        $_where = 'WHERE '.substr($_where, 0, -4);  
        $_sql = "SELECT id FROM {$this->table} $_where LIMIT 1";  
        return $this->execute($_sql)->rowCount();  
    }  
       
    /**
	 * 删除第一条数据
	 *
	 * @access public
	 *
	 * @param array $_param 条件
	 * @return 返回影响的行数,
	 */
    public function delete(Array $_param) {  
        $_where = '';  
        foreach ($_param as $_key=>$_value) {  
            $_where .= $_value.' AND ';  
        }  
        $_where = 'WHERE '.substr($_where, 0, -4);  
        $_sql = "DELETE FROM {$this->table} $_where LIMIT 1";  
        return $this->execute($_sql)->rowCount();  
    }  
       
    /**
	 * 查询数据
	 *
	 * @access public
	 *
	 * @param string|null $_table 另一张表
     * @param array $_fileld 要显示的字段
     * @param array|null $_param 条件
	 * @return 返回查询结果,
	 */  
    public function select( Array $_fileld=array(),$_table=null, Array $_param = array()) {  
        $_limit = $_order = $_where = $_like = '';  
        if (is_array($_param) && !empty($_param)) {  
            $_limit = isset($_param['limit']) ? 'LIMIT '.$_param['limit'] : '';  
            $_order = isset($_param['order']) ? 'ORDER BY '.$_param['order'] : '';  
            if (isset($_param['where'])) {  
                foreach ($_param['where'] as $_key=>$_value) {  
                    $_where .= $_value.' AND ';  
                }  
                $_where = 'WHERE '.substr($_where, 0, -4);  
            }  
            if (isset($_param['like'])) {  
                foreach ($_param['like'] as $_key=>$_value) {  
                    $_like = "WHERE $_key LIKE '%$_value%'";  
                }  
            }  
        }  
        $_selectFields = empty($_fileld)?"*":implode(',', $_fileld);  
        $_table = empty($_table) ? "" : ",".$this->prefix.$_table;  
        $_sql = "SELECT $_selectFields FROM {$this->table}{$_table} $_where $_like $_order $_limit";  
        $_stmt = $this->execute($_sql);  
        $_result = array();  
        while (!!$_objs = $_stmt->fetchObject()) {  
            $_result[] = $_objs;  
        }  
        return $_result;  
    }  
       
    /**
	 * 总记录
	 *
	 * @access public
	 *
     * @param array|null $_param 条件
	 * @return 返回总数,
	 */ 
    public function total( Array $_param = array()) {  
        $_where = '';  
        if (isset($_param['where'])) {  
            foreach ($_param['where'] as $_key=>$_value) {  
                $_where .= $_value.' AND ';  
            }  
            $_where = 'WHERE '.substr($_where, 0, -4);  
        }  
        $_sql = "SELECT COUNT(*) as count FROM {$this->table} $_where";  
        $_stmt = $this->execute($_sql);  
        return $_stmt->fetchObject()->count;  
    }  
       
    /**
	 * 得到下一个id
	 *
	 * @access public
	 *
	 * @return 返回id,
	 */  
    public function nextId() {  
        $_sql = "SHOW TABLE STATUS LIKE '{$this->table}'";  
        $_stmt = $this->execute($_sql);  
        return $_stmt->fetchObject()->Auto_increment;  
    }  
   
   
    /**
	 * 执行SQL语句
	 *
	 * @access public
	 *
     * @param array|null $_param 条件
	 * @return 返回查询结果,
	 */ 
    private function execute($_sql) {  
        try {  
            $_stmt = $this->pdo->prepare($_sql);  
            $_stmt->execute();  
        } catch (PDOException  $ex) {  
            $this->error=$ex->getMessage();
        }  
        return $_stmt;  
    } 
    
    /**
	 * 得到当前执行语句的错误信息
	 *
	 * @access public
	 *
	 * @return 返回错误信息,
	 */ 
    public function getError()
    {
        return $this->error;
    }
}
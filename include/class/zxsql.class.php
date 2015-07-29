<?php
	/****************************************************
	*数据库操作类
	*
	*
	*
	*******************************************************/
	
	class Zxsql{
		
		private $host; //数据库主机
	    private $user; //数据库用户名
	    private $pwd; //数据库用户名密码
	    private $database; //数据库名
	    private $coding; //数据库编码，GBK,UTF8,gb2312
		private $table;
		private $link;
		private $sql;
		private $result;
			
		public function __construct($table,$config_path)
		{
			$config=array();
			if(is_array($config_path)){
				$config=$config_path;
			}else{
				$config=require($config_path);
			}
			
			$this->host = $config['mysql']['host'];
	        $this->user = $config['mysql']['user'];
	        $this->pwd = $config['mysql']['password'];
	        $this->database = $config['mysql']['databse'];
	        $this->coding = $config['mysql']['encoding'];
			$prefix=$config['mysql']['prefix'];
			$this->table=$prefix.$table;
	        $this->connect();
	    }
	 
	    /*数据库连接*/
	    public function connect() {
	            //即使链接
	       	$this->link = mysqli_connect($this->host, $this->user, $this->pwd,$this->database);
	        mysqli_query("SET NAMES {$this->coding}");
	    }
	 
	    /*数据库执行语句，可执行查询添加修改删除等任何sql语句*/
	    public function query($sql) {
	        $this->result = mysqli_query($this->link,$sql);
	        return $this->result;
	    }
		
	 	//创建表格
		 public function create($data)
		 {
			$result =mysqli_num_rows($this->query("SHOW TABLES LIKE '{$this->table}'"));
			if($result>0){
				return "";
			}else{
				$query='';
				 if(is_array($data))
				 {
					 while(list($k,$v) =each($data))
					 {
						 if(empty($query))
						 {
							 $query="{$k} {$v}";
						 }else{
							 $query.=",{$k} {$v}";
						 }
					 }
				 }else{
					 $query=$data;
				 }
				$result=$this->query("CREATE TABLE {$this->table}(
					id INT(10) not null AUTO_INCREMENT,
					$query,
					created datetime,
					updated datetime,
					primary key (id)
					) 
					charset =utf8;");
				if($result){
					return $result;
				}else{
					return "";
				}
			}
		 }
		 
		//查询
		public function select($where)
		{
			 
		}
		
	 	//更新
	 	public function update($data)
		 {
			$query='';
			if(is_array($data))
			{
				while(list($k,$v) =each($data))
				{
					if(empty($query))
					{
						 $query="{$k}={$v}";
					}else{
						 $query.=",{$k}={$v}";
					}
				}
			}else{
				 $query=$data;
			}
			
			return $this->query("UPDATE {$this->table} SET {$query};");
				 
		 }
		 //插入
		 public function insert($sql)
		 {
			 $col="";
			 $val="";
			 if(is_array($sql))
			 {
				 foreach($sql as $k=>$v)
				 {
					 if(empty($col))
					 {
						 $col=$k;
						 $val=$v;
					 }else{
						 $col.=",".$k;
						 $val.=",".$v;
					 }
				 }
			 }else{
				 $val=$sql;
			 }
			 
			 $col=empty($col)?"":"(".$col.")";	
			 
			 return $this->query("INSERT INTO {$this->table}{$col} VALUES ({$val}) ;");
			 	 
		 }
		 //删除
		 public function delete($where)
		 {
			 return $this->query("DELETE FROM {$this->table} WHERE {$where};");
		 }
		 //关闭
		 public function close()
		 {
			 if(!empty($result))
			 {
				 mysqli_free_result($result);
			 }
			 mysqli_close($this->link);
		 }
	}
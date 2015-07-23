<?php
	class zxsql{
		
		private $host; //数据库主机
	    private $user; //数据库用户名
	    private $pwd; //数据库用户名密码
	    private $database; //数据库名
	    private $coding; //数据库编码，GBK,UTF8,gb2312
		private $prefix;
		private $table;
		private $link;
		private $sql;
			
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
			$this->prefix=$config['mysql']['prefix'];
			$this->table=$table;
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
	        $result = mysqli_query($this->link,$sql);
	        return $result;
	    }
	 	
		 public function create($data)
		 {
			 
			$tableComplete=$this->prefix.$this->table; 
			$result =mysqli_num_rows($this->query("SHOW TABLES LIKE '$tableComplete'"));
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
							 $query=",{$k} {$v}";
						 }
					 }
				 }else{
					 $query=$data;
				 }
				$result=$this->query("CREATE TABLE $tableComplete(
					id INT(10) not null AUTO_INCREMENT,
					$query,
					created datetime,
					updated datetime,
					primary key (id)
					) 
					charset =utf8;");
				if($result){
					echo $result;
				}else{
					echo "";
				}
			}
		 }
		 
		 
		 public function select($where,$)
		 {
			 
		 }
	 
	 	public function update()
		 {
			 
		 }
		 
		 public function insert()
		 {
			 
		 }
		 
		 public function delete()
		 {
			 
		 }
		 
		 public function close()
		 {
			 return mysqli_close($this->link);
		 }
	}
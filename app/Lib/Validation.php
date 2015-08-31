<?php 
/* 
*
* 数据验证类，主要是表单验证
*
*
*/
namespace App\Lib;

	
class Validation{
	
	public $error=array();
	
	public function make($request,$pattent)
	{
		foreach($pattent as $key=>$val)
		{
			$arr=explode('|',$val);
			
			if(isset($request[$key]) && !empty($request[$key]))
			{
				foreach($arr as $v)
				{
					$result=$this -> check($request[$key],$v);
				 	if($result != true)
					{
						$this->error[$key][]=$key.$result;
						return false;
					}
				}
			}else{
				if(in_array('required',$arr))
				{
					$this->error[$key][]=$key.' is required';
					return false;
				}
			}
		}
		
		return true;
	}
	
	private function check($value,$patten)
	{
		$result=FALSE;
		$arr=explode(':',$patten,2);
		switch(strtolower($arr[0]))
		{
			case 'required':
				$result= true;
				break;
			case 'number':
				$result= $this->isNum($value)?TRUE:' is not number';
				break;
			case 'email':
				$result= $this->isEmail($value)?TRUE:' is not email';
				break;
			case 'phone':
				$result= $this->isMobile($value)?TRUE:' is not phone';
				break;
			case 'url':
				$result= $this->isUrl($value)?TRUE:' is not url';
				break;
			case 'length':
				$len=explode('-',$arr[1]);
				$result= $this->length($value,3,intval($len[0]),intval($len[1]))?TRUE:'\'s length is not between '.$len[0].' and '.$len[1];
				break;
			case 'min':
				$result= $this->length($value,1,intval($arr[1]))?TRUE:' min length is '.$arr[1];
				break;
			case 'max':
				$result= $this->length($value,2,0,intval($arr[1]))?TRUE:' max length is '.$arr[1];
				break;
			case 'regular':
				$result= $this->regular($value,$arr[1])?TRUE:' is not match';
				break;
			default:
				$result=TRUE;
				break;
		}
		
		return $result;
	}
    /**
     * 数字验证
     * param:$flag : int是否是整数，float是否是浮点型
     */      
    private function isNum($str,$flag = 'int'){
        if(strtolower($flag) == 'int'){
            return ((string)(int)$str === (string)$str) ? true : false;
        }else{
            return ((string)(float)$str === (string)$str) ? true : false;
        }
    }
    /**
     * 邮箱验证
     */      
    private function isEmail($str){
        return preg_match("/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i",$str) ? true : false;
    }
    //手机号码验证
    private function isMobile($str){
        $exp = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';
        if(preg_match($exp,$str)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * URL验证，纯网址格式，不支持IP验证
     */      
    private function isUrl($str){
        return preg_match('#(http|https|ftp|ftps)://([w-]+.)+[w-]+(/[w-./?%&=]*)?#i',$str) ? true : false;
    }
    /**
     * 验证长度
     * @param: string $str
     * @param: int $type(方式，默认min <= $str <= max)
     * @param: int $min,最小值;$max,最大值;
     * @param: string $charset 字符
    */
    private function length($str,$type=3,$min=0,$max=0,$charset = 'utf-8'){
        $len = mb_strlen($str,$charset);
        switch($type){
            case 1: //只匹配最小值
                return ($len >= $min) ? true : false;
                break;
            case 2: //只匹配最大值
                return ($max >= $len) ? true : false;
                break;
            default: //min <= $str <= max
                return (($min <= $len) && ($len <= $max)) ? true : false;
        }
    }
	/**
     * 正则验证
     * @param: string $str
     * @param: string $patten 正则字符串
    */
	private function regular($str,$patten)
	{
        return preg_match($str,$patten)?TRUE:false;
	}
}
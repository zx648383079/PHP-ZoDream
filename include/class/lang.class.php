<?php  
/** 
 * Lang 语言包类 
 * 
 */  
class Lang  
{  
    /** 
     * _options 设置语言包的选项 
     * $this->_options['lang'] 应用程序使用什么语言包.php-gettext支持的所有语言都可以. 
     * 在ubuntu下使用sudo vim /usr/share/il8n/SUPPORTED 主要是utf8编码 
     * $this->_options['domain'] 生成的.mo文件的名字.一般是应用程序名 
     * @var array 
     * @access protected 
     */  
    protected $_options;  
  
    /** 
     * 构造函数 
     * 对象初始化是设置语言包的参数 
     * @param string $lang 
     * @access public 
     * @return void 
     */  
    public function __construct($lang=null) {  
        switch ( strtolower($lang) ) {  
            case 'zh_cn':  
                $this->_options = array('lang' => 'zh_CN.UTF8', 'domain' => 'nways');  
                break;  
            case 'en':  
                $this->_options = array('lang' => 'en_US.UTF8', 'domain' => 'nways');  
                break;  
            case 'en_us':  
                $this->_options = array('lang' => 'en_US.UTF8', 'domain' => 'nways');  
                break;  
            case 'en_gb':  
                $this->_options = array('lang' => 'en_US.UTF8', 'domain' => 'nways');  
                break;  
            default:  
                $this->_options = array('lang' => 'zh_CN.UTF8', 'domain' => 'nways');  
            break;  
        }  
  
        $this->setLang();  
    }  
  
    /** 
     * 设置应用程序语言包的参数，放在$this->_options中 
     * @param mixed $options 
     * @return void 
     */  
    public function setOptions($options) {  
        if (!emptyempty($options)) {  
            foreach ($options as $key => $option) {  
                $this->_options[$key] = $option;  
            }  
        }  
    }  
  
    /** 
     * 设置应用程序语言包 
     * @access public 
     * @return void 
     */  
    public function setLang() {  
        putenv('LANG='.$this->_options['lang']);  
        putenv('LANGUAGE='.$this->_options['lang']);  
        setlocale(LC_ALL, $this->_options['lang']);  
        bindtextdomain($this->_options['domain'], 'asset/lang/');  
        textdomain($this->_options['domain']);  
        bind_textdomain_codeset($this->_options['domain'], 'UTF-8');  
    }  
  
}  
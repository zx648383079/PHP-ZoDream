<?php
declare(strict_types=1);
namespace Module\Disk\Domain;

use Exception;
use Zodream\Infrastructure\Support\Process;

class FFmpeg {

    /**
     * @var string
     */
    public static string $driver = 'ffmpeg';
    /**
     *
     */
    private string $STD = ' 2<&1';
    /**
     *
     */
    private array $quickMethods = array(
        'sameq'
    );

    /**
     *
     */
    private array $as		=	array(
        'b'			=>	'bitrate',
        'r'			=>	'frameRate',
        'fs'		=>	'fileSizeLimit',
        'f'			=>	'forceFormat',
        'force'		=>	'forceFormat',
        'i'			=>	'input',
        's'			=>	'size',
        'ar'		=>	'audioSamplingFrequency',
        'ab'		=>	'audioBitrate',
        'acodec'	=>	'audioCodec',
        'vcodec'	=>	'videoCodec',
        'std'		=>	'redirectOutput',
        'unset'		=>	'_unset',
        'number'	=>	'videoFrames',
        'vframes'	=>	'videoFrames',
        'y'			=>	'overwrite',
        'log'		=>	'logLevel',
    );
    /**
     *
     */
    private array $FFmpegOptionsAS = array(
        'position'			=>	'ss',
        'duration'			=>	't',
        'filename'			=>	'i',
        'offset'			=>	'itsoffset',
        'time'				=>	'timestamp',
        'number'			=>	'vframes',
    );

    /**
     *
     */
    private array $options	=	array();
    /**
     *
     */
    private array $fixForceFormat = array(
        'ogv'	=>	'ogg',
        'jpeg'	=>	'mjpeg',
        'jpg'	=>	'mjpeg',
        'flash'	=>	'flv',
    );
    public $command;

    /**
     * 入口
     * @param null $driver
     * @param null $input
     * @return static
     */
    public static function factory(?string $driver = null, $input = null) {
        return new static($driver, $input);
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     * @throws Exception
     */
    public function __call( $method , $args ) {
        if (array_key_exists($method, $this->as)) {
            return call_user_func_array(
                array($this, $this->as[$method]),
                ( is_array( $args ) ) ? $args : array( $args )
            );
        }
        if (in_array($method, $this->quickMethods)) {
            return call_user_func_array(
                array($this, 'set'),
                ( is_array( $args ) ) ? $args : array( $args )
            );
        }
        throw new Exception( 'The method "'. $method .'" doesnt exist' );
    }

    /**
     * @param $method
     * @param array $args
     * @return	static
     * @throws Exception
     */
    public function call( $method , $args = array() ) {
        if (method_exists ($this, $method)) {
            return call_user_func_array(
                array( $this , $method )  ,
                ( is_array( $args ) ) ? $args : array( $args )
            );
        }
        throw new Exception( 'method doesnt exist' );
    }

    /**
     * @param null $driver
     * @param bool $input
     * @throws Exception
     */
    public function __construct(?string $driver = null, $input = false ) {
        $this->setDriver( $driver );
        if (!empty($input)) {
            $this->input( $input );
        }
        return $this;
    }
    /**
     *   @param	string	$std
     *  @return	static
     *   @access	public
     */
    public function redirectOutput( $std ) {
        if (!empty($std)) {
            $this->STD = ' '.$std;
        }
        return $this;
    }
    /**
     *   @param	string	$output			Output file path
     *   @param	string	$forceFormat	Force format output
     *   @@return	static
     *   @access	public
     */
    public function output($output = null, $forceFormat = null ) {
        $this->forceFormat( $forceFormat );
        $this->command = static::$driver.' '.$this->compilerOptions().' '.$output . $this->STD;
        return $this;
    }

    /**
     * 转化参数
     * @return string
     */
    public function compilerOptions() {
        $options = array();
        foreach ($this->options AS $option => $values) {
            if (!is_array($values)) {
                $options [] = '-'.$option.' '.strval($values);
                continue;
            }
            $items = $this->convertVal($values);
            $options [] = '-'.$option.' '.join(',', $items);
        }
        return join(' ', $options);
    }

    /**
     * 转化命令参数值
     * @param $values
     * @return array
     */
    private function convertVal($values) {
        $items = array();
        foreach ($values AS $item => $val) {
            if (is_null($val)) {
                $items[] = $item;
                continue;
            }
            if (is_array($val)) {
                $val = join(',', $val);
            }
            $val = strval($val);
            if (is_numeric($item) && is_integer($item)) {
                $items[] = $val;
                continue;
            }
            $items[] = $item . '=' . $val;
        }
        return $items;
    }

    /**
     * 强制输入或输出文件格式。格式通常是自动检测输入文件并从输出文件的文件扩展名中猜测出来的
     *   @param	string	$forceFormat	Force format output
     *   @return	static
     *   @access	public
     */
    public function forceFormat( $forceFormat ) {
        if (empty($forceFormat)) {
            return $this;
        }
        $forceFormat = strtolower( $forceFormat );
        if (array_key_exists( $forceFormat, $this->fixForceFormat)) {
            $forceFormat = $this->fixForceFormat[ $forceFormat ];
        }
        return $this->set('f', $forceFormat, false);
    }

    /**
     * 输入文件
     * @param    string $file input file path
     * @return    static
     * @throws Exception
     * @access    public
     * @version    1.2    Fix by @propertunist
     */
    public function input($file) {
        $file = (string)$file;
        if (file_exists($file) && is_file($file)) {
            return $this->set('i', '"'.$file.'"', false);
        }
        if (strstr($file, '%') !== false) {
            return $this->set('i', '"'.$file.'"', false);
        }
        throw new Exception("File $file doesn't exist");
    }
    /**
     *   ATENTION!: This method is experimental
     *
     *   @param	string	$size
     *   @param	string	$start
     *   @param	string	$videoFrames
     *  @return	static
     *   @access	public
     *   @version	1.2	Fix by @propertunist
     */
    public function thumb ($size, $start, $videoFrames = 1) {
        //$input = false;
        if (!is_numeric($videoFrames) || $videoFrames <= 0) {
            $videoFrames = 1;
        }
        $this->audioDisable ();
        $this->size($size);
        $this->position ($start);
        $this->videoFrames ($videoFrames);
        $this->frameRate (1);
        return $this;
    }
    /**
     *   @return	static
     *   @access	public
     */
    public function clear() {
        $this->options = array();
        return $this;
    }
    /**
     *   @param	string	$transpose	http://ffmpeg.org/ffmpeg.html#transpose
     *   @return	static
     *   @access	public
     */
    public function transpose( $transpose = 0 ) {
        if( is_numeric( $transpose )) {
            $this->options['vf']['transpose'] = strval($transpose);
        }
        return $this;
    }
    /**
     *   @return	static
     *   @access	public
     */
    public function vFlip() {
        $this->options['vf']['vflip'] = null;
        return $this;
    }
    /**
     *   @return	object
     *   @access	public
     */
    public function hFlip() {
        $this->options['vf']['hflip'] = null;
        return $this;
    }
    /**
     *   @return	static
     *   @param      $flip	v=vertial OR h=horizontal
     *   @access     public
     *   @example    $ffmpeg->flip('v');
     */
    public function flip( $flip ) {
        if(empty( $flip ) ) {
            return $this;
        }
        $flip = strtolower( $flip );
        if( $flip == 'v' ) {
            return $this->vFlip();
        }
        if( $flip == 'h' ) {
            return $this->hFlip();
        }
        return $this;
    }
    /**
     *   @param	string	$aspect	sample aspect ratio
     *   @return	static
     *   @access	public
     */
    public function aspect( $aspect ) {
         return $this->set('aspect',$aspect,false);
    }
    /**
     *   @param	string	$b	set bitrate (in bits/s)
     *   @return	static
     *   @access	public
     *   @example    $ffmpeg->bitrate('3000/1000');

     */
    public function bitrate( $b ) {
        return $this->set('b', $b,false);
    }
    /**
     *   @param	string	$r	Set frame rate (Hz value, fraction or abbreviation).
     *   @return	static
     *   @access	public
     */
    public function frameRate($r) {
        if(!empty($r) && (is_numeric($r) || preg_match( '/^([0-9]+\/[0-9]+)$/', $r)))  {
            $this->set('r', $r,false);
        }
        return $this;
    }
    /**
     *   @param	string	$s	Set frame size.
     *   @return	static
     *   @access	public
     */
    public function size($s) {
        if (!empty($s) && preg_match( '/^([0-9]+x[0-9]+)$/', $s)) {
            $this->set('s', $s,false);
        }
        return $this;
    }
    /**
     * When used as an input option (before "input"), seeks in this input file to position. When used as an output option (before an output filename), decodes but discards input until the timestamps reach position. This is slower, but more accurate.
     *
     *   @param	string	$s	position may be either in seconds or in hh:mm:ss[.xxx] form.
     *   @return	static
     *   @access	public
     */
    public function position($ss) {
        return $this->set('ss', $ss, false);
    }
    /**
     * 当用作输入选项（之前-i）时，限制从输入文件读取的数据的持续时间。 当用作输出选项时（在输出url之前），在持续时间达到持续时间后停止输出。
     *   @param	string	$t	Stop writing the output after its duration reaches duration. duration may be a number in seconds, or in hh:mm:ss[.xxx] form.
     *   @return	static
     *   @access	public
     */
    public function duration( $t ) {
        return $this->set('t', $t,false);
    }
    /**
     * Set the input time offset in seconds. [-]hh:mm:ss[.xxx] syntax is also supported. The offset is added to the timestamps of the input files.
     *
     *   @param	string	$t	Specifying a positive offset means that the corresponding streams are delayed by offset seconds.
     *   @return	static
     *   @access	public
     */
    public function itsoffset( $itsoffset ) {
        return $this->set('itsoffset', $itsoffset,false);
    }
    /**
     *@return	static
     */
    public function audioSamplingFrequency( $ar ) {
        return $this->set('ar',$ar,false);
    }
    /**
     *@return	static
     */
    public function audioBitrate( $ab ) {
        return $this->set('ab', $ab , false );
    }
    /**
     *@return	static
     */
    public function audioCodec( $acodec = 'copy' ) {
        return $this->set('acodec', $acodec,false);
    }
    /**
     *@return	static
     */
    public function audioChannels( $ac ) {
        $this->set('ac', $ac,false);
    }
    /**
     *@return	static
     */
    public function audioQuality( $aq ) {
        return $this->set('aq', $aq , false );
    }
    /**
     *@return	static
     */
    public function audioDisable() {
        return $this->set('an', null, false);
    }
    /**
     *   @param	string	$number
     *   @return	static
     *   @access	public
     */
    public function videoFrames( $number ) {
        return $this->set( 'vframes', $number );
    }
    /**
     *	@param string	$vcodec
     *	@return static
     */
    public function videoCodec( $vcodec = 'copy' ) {
        return $this->set('vcodec' , $vcodec );
    }
    /**
     *	@return static
     */
    public function videoDisable() {
        return $this->set('vn', null,false);
    }
    /**
     * 覆盖输出文件
     *	@return static
     */
    public function overwrite() {
        return $this->set('y', null,false);
    }
    /**
     *	@param string	$fs
     *	@return static
     */
    public function fileSizeLimit( $fs ) {
        return $this->set('fs' , $fs , false );
    }
    /**
     *	@param string	$progress
     *	@return static
     */
    public function progress( $progress ) {
        return $this->set('progress',$progress);
    }
    /**
     *	@param integer	$pass
     *	@return static
     */
    public function pass( $pass ) {
        if(!is_numeric( $pass ) ) {
            return $this;
        }
        $pass = intval( $pass );
        if( $pass == 1 || $pass == 2 ) {
            $this->options['pass'] = $pass;
        }
        return $this;
    }

    /**
     * @param    string $append
     * @return    Process
     * @throws Exception
     * @access    public
     */
    public function ready( $append = null ) {
        /**
         *	Check if command is empty
         */
        if( empty( $this->command ) ) {
            $this->output();
        }
        if(!empty( $this->command )) {
            return Process::factory($this->command . $append );
        }
        throw new Exception('Cannot execute a blank command');
    }
    /**
     *
     *   @return	static
     *   @param	string	ffmpeg
     *   @access	public
     */
    public function setDriver(?string $driver) {
        if (!empty($driver)) {
            self::$driver = $driver;
        }
        return $this;
    }
    /**
     *	@param string	$key
     *	@param mixed	$value
     *	@param bool	$append
     *	@return static
     */
    public function set(string $key, $value = null, bool $append = false ) {
        $key = preg_replace( '/^(\-+)/' , '' , $key );
        if(empty($key) ) {
            return $this;
        }
        if( array_key_exists( $key , $this->FFmpegOptionsAS ) ) {
            $key = $this->FFmpegOptionsAS[ $key ];
        }
        if( $append === false ) {
            $this->options[ $key ] = $value;
            return $this;
        }
        if( !array_key_exists( $key , $this->options )  ) {
            $this->options[ $key ] = array($value);
            return $this;
        }
        if( !is_array( $this->options[ $key ] ) ) {
            $this->options[ $key ] = array($this->options[ $key ],$value);
            return $this;
        }
        $this->options[ $key ][] = $value;
        return $this;
    }
    /**
     *	@param string	$key
     *	@return static
     */
    public function _unset(string $key ) {
        if( array_key_exists( $key , $this->options ) ) {
            unset( $this->options[ $key ] ) ;
        }
        return $this;
    }
    /**
     *	@return object Self
     *	@access	public
     */
    public function grayScale() {
        return $this->set('pix_fmt', 'gray');
    }

    /**
     * 设置日志等级
     *   @param	string	$level
     *   @return	static
     *   @access	public
     */
    public function logLevel(string $level = 'verbose') {
        $level = strtolower( $level );
        if( in_array( $level, array('quiet', 'panic', 'fatal', 'error', 'warning', 'info', 'verbose', 'debug') ) ) {
            return $this->set('loglevel',$level );
        }
        throw new Exception('The option does not valid in loglevel');
    }


}
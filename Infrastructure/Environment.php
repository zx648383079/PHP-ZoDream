<?php
namespace Infrastructure;

use Zodream\Infrastructure\Request;
class Environment {
	/**
	 * 获取操作系统版本
	 */
	public static function getOS() {
		return PHP_OS;
	}
	
	/**
	 * 获取php版本
	 */
	public static function getPhpVersion() {
		return phpversion();
	}
	
	/**
	 * 获取服务器
	 */
	public static function getServer() {
		return Request::getInstance()->server('SERVER_SOFTWARE');
	}
	
	/**
	 * 获取域名
	 */
	public static function getName() {
		return Request::getInstance()->server('SERVER_NAME');
	}
	
	/**
	 * 是否支持 allow_url_fopen
	 */
	public static function getUrlFopen() {
		return ini_get('allow_url_fopen');
	}
	
	/**
	 * 是否是安全模式
	 */
	public static function getSafeMode() {
		return ini_get('safe_mode');
	}
	
	/**
	 * 获取GB图形库版本
	 */
	public static function getGbVersion() {
		if (!function_exists('phpinfo')) {
			if (function_exists('imagecreate')) {
				return '2.0';
			}
			return 0;
		}
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		if (preg_match('/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i', $info, $match)) {
			return $match[1];
		}
		return 0;
	}
	
	/**
	 * 判断mysql是否支持
	 * @return boolean
	 */
	public static function getMysql() {
		return function_exists('mysql_connect');
	}
	
	/**
	 * 判断mysqli是否支持
	 * @return boolean
	 */
	public static function getMysqli() {
		return function_exists('mysqli_connect');
	}
	
	/**
	 * 判断pdo是否支持
	 * @return boolean
	 */
	public static function getPdo() {
		return class_exists('PDO');
	}
	
	/**
	 * 判断读的功能
	 * @param string $file
	 * @return boolean
	 */
	public static function getReadAble($dir) {
		return is_readable($dir);
	}
	/**
	 * 判断写的功能
	 * @param string $dir
	 * @return boolean
	 */
	public static function getWriteAble($dir) {
		$testFile = '_zodream.txt';
		$fp = @fopen($dir.$testFile, 'w');
		if (!$fp) {
			return true;
		}
		fclose($fp);
		$rs = @unlink($dir.'/'.$testFile);
		return boolval($rs);
	}
	
	public static function getFileByDir($dir = '/') {
		$files    = $dirs = array();
		$dir = '/'.trim($dir, '/');
		$dirList = @scandir(APP_DIR.$dir);
		foreach($dirList as $file) {
			if ( $file != '..' && $file != '.' ) {
				$fullfile = rtrim($dir, '/').'/'.$file;
				if (is_dir(APP_DIR. $fullfile)) {
					$dirs[] = array(
							'name' => $file,
							'kind' => 'dir',
							'full' => $fullfile
					);
				} else {
					$files[] = array(
							'name' => $file,
							'kind' => 'file',
							'full' => $fullfile
					);
				}
			}
		}
		return array(
				'dirs' => $dirs,
				'files' => $files
		);
	}
	
	/**
	 * 遍历获取目录下的指定类型的文件
	 * @param $path
	 * @param array $files
	 * @return array
	 */
	public static function getfiles($path, $allowFiles, &$files = array())
	{
	    if (!is_dir($path)) return null;
	    if(substr($path, strlen($path) - 1) != '/') $path .= '/';
	    $handle = opendir($path);
	    while (false !== ($file = readdir($handle))) {
	        if ($file != '.' && $file != '..') {
	            $path2 = $path . $file;
	            if (is_dir($path2)) {
	                self::getfiles($path2, $allowFiles, $files);
	            } else {
	                if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
	                    $files[] = array(
	                        'url'=> substr($path2, strlen(APP_DIR)),
	                        'mtime'=> filemtime($path2)
	                    );
	                }
	            }
	        }
	    }
	    return $files;
	}
}
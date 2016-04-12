<?php
namespace Service\Install;

use Infrastructure\Environment;
use Zodream\Domain\Generate\Generate;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Config;
use Zodream\Infrastructure\Request;

class HomeController extends Controller {
	public function prepare() {
		if (file_exists('install.off')) {
			$this->show('@《网站管理系统》安装程序已锁定。如果要重新安装，请删除<b>../install.off</b>文件！');
		}
	}

	function indexAction() {
		$this->show();
	}

    function environmentAction() {
        $this->show(array(
            'name' => Environment::getName(),
            'os' => Environment::getOS(),
            'server' => Environment::getServer(),
            'phpversion' => Environment::getPhpVersion(),
            'appdir' => APP_DIR,
            'allowUrlFopen' => Environment::getUrlFopen(),
            'safeMode' => Environment::getSafeMode(),
            'gd' => Environment::getGbVersion(),
            'mysql' => Environment::getMysql(),
            'mysqli' => Environment::getMysqli(),
            'pdo' => Environment::getPdo(),
            'temp' => Environment::getWriteAble(APP_DIR. '/temp'),
            'log' => Environment::getWriteAble(APP_DIR. '/log')
        ));
    }

	function databaseAction() {
		$this->show();
	}

    function databasePost() {
        $handle = opendir(APP_DIR. '/Service');
        $generate = new Generate();
        $generate->makeConfig(array(), 'config');
        $generate->createDatabase(Request::post('db.database'));
        $data = Config::getValue();
        $data['db'] = array_merge($data['db'], Request::post('db'));
        while (false !== ($file = readdir($handle))) {
            if ('.' == $file || '..' == $file || 'config' == $file || APP_MODULE == $file) {
                continue;
            }
            $generate->makeConfig($data, $file);
        }
        Config::getInstance()->reset();
        $generate->importSql(APP_DIR.'/document/zodream.sql');
        Redirect::to('complete');
    }

    function completeAction() {
        file_put_contents('install.off', '');
        $this->show();
    }

	/**
	 * 采集测试
	 */
	function spiderAction() {
		$content = file_get_contents('');
		$this->show('@<title>TEST</title><br>测试结果：<b>'.(empty($content) ?  '不':'').'支持采集</b>');
	}
}
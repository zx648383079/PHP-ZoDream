<?php
namespace Service\Install;

use Infrastructure\Environment;
use Zodream\Domain\Generate\Generate;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Config;
use Zodream\Infrastructure\Database\Schema\Schema;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Url\Url;

class HomeController extends Controller {

	function indexAction() {
        if (is_file('install.off')) {
            return $this->showContent('《网站管理系统》安装程序已锁定。如果要重新安装，请删除<b>../install.off</b>文件！');
        }
		return $this->show([
		    'title' => '开始'
        ]);
	}

    function environmentAction() {
        return $this->show(array(
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
		return $this->show();
	}

	function dbsAction() {
        Config::setValue('db', Request::post('host,port,database information_schema,user,password'));
        $schema = new Schema();
        return $this->ajax([
            'status' => 1,
            'data' => $schema->getAllDatabase()
        ]);
    }

    function importAction() {
        $handle = opendir(APP_DIR. '/Service');
        $generate = new Generate();
        $data = Config::getValue();
        $data['db'] = array_merge($data['db'], Request::post('db'));
        Config::setValue('db', $data['db']);
        $generate->makeConfig(array(), 'config');
        $generate->createDatabase(Request::post('db.database'));
        unset($data['view']);
        while (false !== ($file = readdir($handle))) {
            if ('.' == $file || '..' == $file ||
                'Bootstrap.php' == $file ||
                'config' == $file ||
                APP_MODULE == $file) {
                continue;
            }
            $generate->makeConfig($data, $file);
        }
        Config::getInstance()->reset();
        $generate->importSql(APP_DIR.'/document/zodream.sql');
        return $this->ajax([
            'status' => 1,
            'url' => (string)Url::to(['complete'])
        ]);
    }

    function completeAction() {
        file_put_contents('install.off', '');
        return $this->show();
    }

	/**
	 * 采集测试
	 */
	function spiderAction() {
		$content = file_get_contents('http://zodream.cn');
		return $this->show('@<title>TEST</title><br>测试结果：<b>'.(empty($content) ?  '不':'').'支持采集</b>');
	}
}
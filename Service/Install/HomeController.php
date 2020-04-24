<?php
namespace Service\Install;


use Infrastructure\Environment;
use Zodream\Module\Gzo\Domain\GenerateModel;
use Zodream\Module\Gzo\Domain\Generator\ModuleGenerator;

class HomeController extends Controller {

    protected function rules() {
        return [
            '*' => '*',
            'init' => 'cli'
        ];
    }

    public function indexAction() {
		return $this->show([
		    'title' => '开始'
        ]);
	}

    public function environmentAction() {
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
            'temp' => Environment::getWriteAble(APP_DIR. '/data/temp'),
            'log' => Environment::getWriteAble(APP_DIR. '/data/log')
        ));
    }

    public function databaseAction() {
		return $this->show();
	}

    public function dbsAction() {
        config([
            'db' => app('request')->get('host,port,database information_schema,user,password')
        ]);
        return $this->json([
            'status' => 1,
            'data' => Schema::getAllDatabase()
        ]);
    }

    public function importAction() {
        $handle = opendir(APP_DIR. '/Service');
        $data = config()->get();
        $data['db'] = array_merge($data['db'], app('request')->get('db'));
        config([
            'db' => $data['db']
        ]);
        ModuleGenerator::renderConfigs('config', []);
        GenerateModel::schema(app('request')->get('db.database'))
            ->create();
        unset($data['view']);
        while (false !== ($file = readdir($handle))) {
            if ('.' == $file || '..' == $file ||
                'Bootstrap.php' == $file ||
                'config' == $file ||
                app('app.module') == $file) {
                continue;
            }
            ModuleGenerator::renderConfigs($file, $data);
        }
        config()->reset();
        return $this->json([
            'status' => 1,
            'url' => url(['complete'])
        ]);
    }

    public function completeAction() {
        file_put_contents('install.off', '');
        return $this->show();
    }

	/**
	 * 采集测试
	 */
    public function spiderAction() {
		$content = file_get_contents('http://zodream.cn');
		return $this->showContent('<title>TEST</title><br>测试结果：<b>'.(empty($content) ?  '不':'').'支持采集</b>');
	}

	public function initAction() {
        $request = app('request');
        $host = $request->read('localhost', '请输入数据主机：');
        $port = $request->read('3306', '请输入数据主机端口：');
        $database = $request->read('zodream', '请输入数据库：');
        $user = $request->read('root', '请输入数据库账号：');
        $password = $request->read('', '请输入数据库密码：');
        $prefix = $request->read('', '请输入表前缀：');
        ModuleGenerator::renderConfigs('config', [
            'db' => compact('host', 'port', 'database', 'user', 'password', 'prefix')
        ]);
        $yes = $request->read('', '是否安装其他模块(Y/N)：');
        if (empty($yes) || strtoupper($yes) !== 'Y') {
            return $this->showContent('安装完成！');
        }
    }
}
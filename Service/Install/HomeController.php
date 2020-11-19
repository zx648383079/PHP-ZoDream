<?php
namespace Service\Install;

use Infrastructure\Environment;
use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Infrastructure\Http\Request;
use Zodream\Module\Gzo\Domain\GenerateModel;
use Zodream\Module\Gzo\Domain\Generator\ModuleGenerator;
use Zodream\Module\Gzo\Service\ModuleController;
use Zodream\Module\Gzo\Service\SqlController;

class HomeController extends Controller {

    protected function rules() {
        return [
            '*' => '*',
            'init' => 'cli'
        ];
    }

    public function indexAction() {
		return $this->show();
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

    public function dbsAction(Request $request) {
        $configs = $request->get('db');
        $configs['database'] = 'information_schema';
        config([
            'db' => $configs
        ]);
        try {
            return (new SqlController())->schemaAction();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function importAction(Request $request) {
        $configs = $request->get('db');
        $handle = opendir(APP_DIR. '/Service');
        $data = config()->get();
        $data['db'] = array_merge($data['db'], $configs);
        config([
            'db' => $data['db']
        ]);
        ModuleGenerator::renderConfigs('config', [
            'db' => $data['db']
        ]);
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
        return $this->renderData([
            'url' => url('./module')
        ]);
    }

    public function moduleAction() {
        $module_list = ModuleController::getModuleList();
        return $this->show(compact('module_list'));
    }

    public function importModuleAction($module, $user) {
        $data = [];
        foreach ($module['checked'] as $item) {
            $uri = $module['uri'][$item];
            $data[$uri] = 'Module\\'.$item;
        }
        ModuleController::installModule($data, [
            'install', 'seeder'
        ]);
        ModuleGenerator::renderConfigs('config', [
            'db' => config('db'),
            'modules' => $data,
            'auth' => [
                'home' => '/auth',
                'model' => 'Module\Auth\Domain\Model\UserModel',
            ],
            'view' => [
                'asset_directory' => 'assets',
            ],
            'i18n' => [
                'language' => null//'zh-cn',
            ]
        ]);
        try {
            AuthRepository::createAdmin($user['email'], $user['password']);
        } catch (\Exception $ex) {
        }
        return $this->renderData([
            'url' => url('./complete')
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
        $db = compact('host', 'port', 'database', 'user', 'password', 'prefix');
        config([
            'db' => $db
        ]);
        try {
            GenerateModel::schema($db['database'])
                ->create();
        } catch (\PDOException $ex) {
            return $this->showContent(sprintf('请确认数据库【%s】是否创建？请手动创建', $database));
        }
//        $yes = $request->read('', '是否安装其他模块(Y/N)：');
//        if (empty($yes) || strtoupper($yes) !== 'Y') {
//            return $this->showContent('安装完成！');
//        }
        $module_list = ModuleController::getModuleList();
        $modules = [
            'auth' => 'Module\Auth',
            'blog' => 'Module\Blog',
            'seo' => 'Module\SEO',
            'contact' => 'Module\Contact',
        ];
        echo '模块列表：', PHP_EOL;
        foreach ($module_list as $i => $item) {
            echo $i + 1, ',', $item, (in_array('Module\\'.$item, $modules) ? '(默认)' : ''), PHP_EOL;
        }
        while (($num = $request->read('0', '请选要安装的模块：')) > 0) {
            if ($num > count($module_list)) {
                continue;
            }
            $uri = $request->read('', '请输入模块uri：');
            if (empty($uri)) {
                continue;
            }
            $modules[trim($uri, '/')] = 'Module\\'.$module_list[$num - 1];
        }
        ModuleController::installModule($modules, [
            'install', 'seeder'
        ]);
        ModuleGenerator::renderConfigs('config', [
            'db' => $db,
            'modules' => $modules,
            'auth' => [
                'home' => '/auth',
                'model' => 'Module\Auth\Domain\Model\UserModel',
            ],
            'view' => [
                'asset_directory' => 'assets',
            ],
            'i18n' => [
                'language' => null//'zh-cn',
            ]
        ]);
        $email = $request->read('', '请输入管理员邮箱：');
        $password = $request->read('', '请输入管理员密码：');
        if (empty($email) || empty($password)) {
            echo '管理员账户未创建', PHP_EOL;
        } else {
            try {
                AuthRepository::createAdmin($email, $password);
            } catch (\Exception $ex) {
                echo $ex->getMessage(), PHP_EOL;
            }
        }
        return $this->showContent('安装完成！');
    }
}
<?php
namespace Service\Install;

use Infrastructure\Environment;
use Module\Auth\Domain\Repositories\AuthRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Module\Gzo\Domain\Generator\ModuleGenerator;
use Zodream\Module\Gzo\Domain\Repositories\DatabaseRepository;
use Zodream\Module\Gzo\Domain\Repositories\ModuleRepository;

class HomeController extends Controller {

    private string $lockFile = APP_DIR .'/data/install.off';
    private array $mustModuleItems = [
        'seo' => 'SEO',
        'auth' => 'Auth',
        // 'contact' => 'Contact',
    ];

    public function rules() {
        return [
            '*' => '*',
            'init' => 'cli'
        ];
    }

    public function indexAction() {
        if (is_file($this->lockFile)) {
            return $this->redirectWithMessage('/', '已安装完成');
        }
		return $this->show();
	}

    public function environmentAction() {
        $data = array(
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
            'folders' => [

            ],
        );
        $folderItems = [
            [
                'name' => 'Session文件夹',
                'path' => APP_DIR. '/data/temp',
            ],
            [
                'name' => '缓存文件夹',
                'path' => APP_DIR. '/data/cache',
                'required' => true
            ],
            [
                'name' => '文件上传文件夹',
                'path' => APP_DIR. '/html/assets/upload',
                'required' => true
            ],
            [
                'name' => '错误日志文件夹',
                'path' => APP_DIR. '/data/log',
                'required' => true
            ],
        ];
        foreach ($folderItems as $item) {
            if (!isset($item['required'])) {
                $item['required'] = false;
            }
            $item['writeable'] = Environment::getWriteAble($item['path']);
            $item['path'] = str_replace('\\', '/', $item['path']);
            $data['folders'][] = $item;
        }
        return $this->show($data);
    }

    public function databaseAction() {
		return $this->show();
	}

    public function dbsAction(Request $request) {
        $configs = array_merge(config('database.connections'), $request->get('db', []));
        config()->set('database', [
            'connections' => $configs
        ]);
        try {
            return $this->renderData(DatabaseRepository::schemas());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function importAction(Request $request) {
        try {
            $configs = $request->get('db');
            $data = config('database');
            $data['connections'] = array_merge($data['connections'], $configs);
            ModuleGenerator::renderConfigs('database', $data);
            $data['connections']['database'] = 'information_schema';
            config()->set('database', $data);
            DatabaseRepository::schemaCreate($configs['database']);
            config()->set('database', null);
            return $this->renderData([
                'url' => url('./module')
            ]);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function moduleAction() {
        $module_list = ModuleRepository::moduleList();
        $must_items = [];
        foreach ($this->mustModuleItems as $path => $name) {
            $must_items[$name] = $path;
        }
        return $this->show(compact('module_list', 'must_items'));
    }

    public function importModuleAction(array $module, array $user) {
        try {
            foreach ($this->mustModuleItems as $uri => $item) {
                if (!empty($module['uri'][$item])) {
                    $uri = $module['uri'][$item];
                }
                ModuleRepository::install($uri, 'Module\\'.$item, true, true);
            }
            foreach ($module['checked'] as $item) {
                if (in_array($item, $this->mustModuleItems)) {
                    continue;
                }
                $uri = $module['uri'][$item];
                ModuleRepository::install($uri, 'Module\\'.$item, true, true);
            }
            AuthRepository::createAdmin($user['email'], $user['password']);
            return $this->renderData([
                'url' => url('./complete')
            ]);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function completeAction() {
        file_put_contents($this->lockFile, '');
        return $this->show();
    }

	/**
	 * 采集测试
	 */
    public function spiderAction() {
		$content = file_get_contents('https://zodream.cn');
		return $this->showContent('<title>TEST</title><br>测试结果：<b>'.(empty($content) ?  '不':'').'支持采集</b>');
	}

	public function initAction(Request $request) {
        $host = $request->post('请输入数据主机：', 'localhost');
        $port = $request->post('请输入数据主机端口：', '3306');
        $database = $request->post('请输入数据库：', 'zodream');
        $user = $request->post('请输入数据库账号：', 'root');
        $password = $request->post('请输入数据库密码：', '');
        $prefix = $request->post('请输入表前缀：', '');
        $db = compact('host', 'port', 'database', 'user', 'password', 'prefix');
        config([
            'database' => [
                'connections' => $db,
            ]
        ]);
        try {
            DatabaseRepository::schemaCreate($db['database']);
        } catch (\Exception $ex) {
            return $this->showContent(sprintf('请确认数据库【%s】是否创建？请手动创建', $database));
        }
        ModuleGenerator::renderConfigs('database', [
            'connections' => $db,
        ]);
        $yes = $request->post('是否安装其他模块(Y/N)：', '');
        if (empty($yes) || strtoupper($yes) !== 'Y') {
            return $this->showContent('安装完成！');
        }
        $module_list = ModuleRepository::moduleList();
        $modules = $this->mustModuleItems;
        echo '模块列表：', PHP_EOL;
        foreach ($module_list as $i => $item) {
            echo $i + 1, ',', $item, (in_array($item, $modules) ? '(默认)' : ''), PHP_EOL;
        }
        while (($num = $request->post('请选要安装的模块：', '0')) > 0) {
            if ($num > count($module_list)) {
                continue;
            }
            $uri = $request->post('请输入模块uri：', '');
            if (empty($uri)) {
                continue;
            }
            $modules[trim($uri, '/')] = $module_list[$num - 1];
        }
        foreach ($this->mustModuleItems as $uri => $item) {
            if (in_array($item, $modules)) {
                $uri = array_search($item, $modules);
            }
            ModuleRepository::install($uri, 'Module\\'.$item, true, true);
        }
        foreach ($modules as $path => $module) {
            if (in_array($module, $this->mustModuleItems)) {
                continue;
            }
            ModuleRepository::install($path, 'Module\\'.$module, true, true);
        }

        $email = $request->post('请输入管理员邮箱：', '');
        $password = $request->post('请输入管理员密码：', '');
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
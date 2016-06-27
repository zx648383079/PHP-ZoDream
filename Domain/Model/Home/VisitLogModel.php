<?php
namespace Domain\Model\Home;
/**
 * 访客记录
CREATE TABLE IF NOT EXISTS `zd_visit_log` (
`id` INT NOT NULL AUTO_INCREMENT,
`ip` VARCHAR(20) NOT NULL COMMENT 'IP地址',
`browser` VARCHAR(45) NULL COMMENT '浏览器',
`browser_version` VARCHAR(45) NULL COMMENT '浏览器版本',
`os` VARCHAR(45) NULL COMMENT '操作系统',
`os_version` VARCHAR(45) NULL COMMENT '操作系统版本',
`session` VARCHAR(45) NULL COMMENT '会话标识',
`url` TEXT NULL COMMENT '请求网址',
`referer` VARCHAR(200) NULL COMMENT '来路',
`agent` VARCHAR(255) NULL COMMENT '代理',
`create_at` DATETIME NULL COMMENT '发生时间',
PRIMARY KEY (`id`))
ENGINE = InnoDB DEFAULT CHARSET=UTF8;
 */
use Domain\Model\Model;
use Zodream\Domain\Routing\Url;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Traits\SingletonPattern;

class VisitLogModel extends Model {

	use SingletonPattern;

	protected $table = 'visit_log';
	
	protected $fillAble = array(
		'ip',
		'browser',
		'browser_version',
		'os',
		'os_version',
		'session',
		'url',
		'referer',
		'agent',
		'create_at'
	);

	public static function addLog() {
		$os = Request::os();
		$browser = Request::browser();
		return static::getInstance()->add([
			'ip' => Request::ip(),
			'browser' => $browser[0],
			'browser_version' => $browser[1],
			'os' => $os[0],
			'os_version' => $os[1],
			'referer' => Url::referrer(),
			'url' => Url::to(),
			'session' => Factory::session()->id(),
			'agent' => Request::server('HTTP_USER_AGENT', '-'),
			'create_at' => TimeExpand::format()
		]);
	}

	/**
	 * 获取每天的状态
	 * @param array|string $where
	 * @return array
	 */
	public static function getDayStatus($where = null) {
		return self::getStatus($where, 'DAYOFMONTH', 31);
	}

	/**
	 * 获取每小时的状态
	 * @param array|string $where
	 * @return array
	 */
	public static function getHourStatus($where = null) {
		return self::getStatus($where, 'HOUR', 24);
	}

	public static function getWeekStatus($where = null) {
		return self::getStatus($where, 'DAYOFWEEK', 7);
	}

	public static function getMonthStatus($where = null) {
		return self::getStatus($where, 'MONTH', 12);
	}

	public static function getYearStatus($where = null) {
		return self::getStatus($where, 'YEAR');
	}

	/**
	 * 获取IP最后访问时间及来路
	 * @param string|array $where
	 * @return array
	 */
	public static function getLast($where = null) {
		return static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 15
		], 'ip, MAX(create_at) as create_at, referer');
	}

	/**
	 * 获取所有的月份
	 * @param string|array $where
	 * @return array
	 */
	public static function geAllMonth($where = null) {
		return static::getInstance()->findAll([
			'where' => $where,
			'group' => '1,2,3',
			'order' => '1,2,3',
			'limit' => 30
		], 'YEAR(create_at) as year, MONTH(create_at) as month, DAYOFMONTH(create_at) as day, COUNT(*) as count,COUNT(DISTINCT ip) as countIp');
	}

	/**
	 * 获取前三十名访问的网址
	 * @param array|string $where
	 * @return mixed
	 */
	public static function geTopUrl($where = null) {
		return static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 30
		], 'url, COUNT(*) as count');
	}

	/**
	 * 获取前三十名访问者的IP
	 * @param array|string $where
	 * @return mixed
	 */
	public static function geTopIp($where = null) {
		return static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 30
		], 'ip, COUNT(*) as count');
	}

	/**
	 * 获取前三十名访问者浏览器
	 * @param array|string $where
	 * @return mixed
	 */
	public static function geTopBrowser($where = null) {
		return static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 30
		], 'browser, COUNT(*) as count');
	}

	/**
	 * 获取前三十名访问者系统
	 * @param array|string $where
	 * @return mixed
	 */
	public static function geTopOs($where = null) {
		return static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 30
		], 'os, COUNT(*) as count');
	}

	/**
	 * 获取前三十名访问者国家
	 * @param array|string $where
	 * @return mixed
	 */
	public static function geTopCountry($where = null) {
		return static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 30
		], 'RIGHT(ip,INSTR(REVERSE(ip),\".\")-1) as country, COUNT(*) as count');
	}

	/**
	 * 获取前三十名搜索引擎来源
	 * @param array|string $where
	 * @return mixed
	 */
	public static function getTopSearch($where = null) {
		if (empty($where)) {
			$where = [];
		}
		if (!is_array($where)) {
			$where = (array)$where;
		}
		$where[] = "(INSTR(referer,'?q=') OR INSTR(referer, '?wd=') OR INSTR(referer,'?p=') OR INSTR(referer,'?query=') OR INSTR(referer,'?qkw=') OR INSTR(referer,'?search=') OR INSTR(referer,'?qr=') OR INSTR(referer,'?string='))";
		$data = static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 30
		], 'referer,COUNT(*) as count');
		$args = [];
		$urls = [];
		foreach ($data as $item) {
			if(preg_match('#//([\w\.]+?)/.*?\?[a-z]+=([^&]+)#i', $item['referer'], $match)) {
				if (!array_key_exists($match[1], $urls)) {
					$urls[$match[1]] = 0;
				}
				$count = intval($item['count']);
				$urls[$match[1]] += $count;
				$tags = explode(' ', urldecode($match[2]));
				if (!array_key_exists($match[2], $args)) {
					$args[$match[2]] = 0;
				}
				$args[$match[2]] += $count;
				if (count($tags) == 1) {
					continue;
				}
				foreach ($tags as $tag) {
					if (empty($tag)) {
						continue;
					}
					if (!array_key_exists($tag, $args)) {
						$args[$tag] = 0;
					}
					$args[$tag] += $count;
				}
			}
		}
		arsort($args);
		arsort($urls);
		return [
			$args,
			$urls
		];
	}

	/**
	 * 获取前三十名外链
	 * @param array|string $where
	 * @return mixed
	 */
	public static function getTopReferer($where = null) {
		if (empty($where)) {
			$where = [];
		}
		if (!is_array($where)) {
			$where = (array)$where;
		}
		$allUrls = static::getInstance()->findAll([
			'where' => $where
		], 'url');
		$where[] = 'referer NOT LIKE "%'.Url::getHost().'%"';
		$args = static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => '2 DESC',
			'limit' => 20
		], 'referer,COUNT(*) as count');
		$urls = static::getInstance()->findAll([
			'where' => $where
		], 'url');
		return [
			$args,
			$urls,
			$allUrls
		];
	}

	/**
	 * @param $where
	 * @param string $type
	 * @param null $length
	 * @return array
	 */
	public static function getStatus($where = null, $type = 'DAYOFMONTH', $length = null) {
		$flowCount = [];
		$i = 0;
		if (empty($length)) {
			$i = 2015;
			$length = date('Y') + 1;
		} else if ($length != 24) {
			$i = 1;
			$length ++;
		}
		for (; $i < $length; $i++) {
			$flowCount[$i] = [
				0, // PV总计
				0,  // UV
				0   //IP
			];
		}
		$args = static::getInstance()->findAll([
			'where' => $where,
			'group' => 1,
			'order' => 1
		], $type.'(create_at) as d, COUNT(*) as c');
		$max = 0;
		foreach ($args as $item) {
			if (!array_key_exists($item['d'], $flowCount)) {
				continue;
			}
			$flowCount[$item['d']][0] = intval($item['c']);
			if ($item['c'] > $max) {
				$max = $item['c'];
			}
		}

		$uvs = $ips = static::getInstance()->findAll([
			'where' => $where,
			'group' => '1,2',
			'order' => 1
		], $type.'(create_at) as d, session');
		foreach ($uvs as $item) {
			if (!array_key_exists($item['d'], $flowCount)) {
				continue;
			}
			$flowCount[$item['d']][1] ++;
		}

		$ips = static::getInstance()->findAll([
			'where' => $where,
			'group' => '1,2',
			'order' => 1
		], $type.'(create_at) as d, ip');
		foreach ($ips as $item) {
			if (!array_key_exists($item['d'], $flowCount)) {
				continue;
			}
			$flowCount[$item['d']][2] ++;
		}
		return [
			$flowCount,
			$max,
			$args,
			$ips
		];
	}
}
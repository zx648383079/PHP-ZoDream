<?php
namespace Service\Admin;

/**
 * 流量统计
 */
use Domain\Model\Home\VisitLogModel;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;

class FlowController extends Controller {
	function indexAction() {
		$this->show(array(

		));
	}

	function statusAction($type = 0) {
		$type = intval($type);
		switch ($type) {
			case 8:
				$title = '每年流量';
				$args = VisitLogModel::getYearStatus();
				break;
			case 7:
				$title = '去年流量';
				list($begin, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::LASTYEAR);
				$args = VisitLogModel::getMonthStatus([
					'create_at' => [
						'between',
						TimeExpand::format($begin),
						TimeExpand::format($end)
					]
				]);
				break;
			case 6:
				$title = '今年流量';
				list($begin, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::YEAR);
				$args = VisitLogModel::getMonthStatus([
					'create_at' => [
						'between',
						TimeExpand::format($begin),
						TimeExpand::format($end)
					]
				]);
				break;
			case 5:
				$title = '上月流量';
				list($begin, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::LASTMONTH);
				$args = VisitLogModel::getDayStatus([
					'create_at' => [
						'between',
						TimeExpand::format($begin),
						TimeExpand::format($end)
					]
				]);
				break;
			case 4:
				$title = '本月流量';
				list($begin, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::MONTH);
				$args = VisitLogModel::getDayStatus([
					'create_at' => [
						'between',
						TimeExpand::format($begin),
						TimeExpand::format($end)
					]
				]);
				break;
			case 3:
				$title = '上周流量';
				list($begin, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::LASTWEEK);
				$args = VisitLogModel::getWeekStatus([
					'create_at' => [
						'between',
						TimeExpand::format($begin),
						TimeExpand::format($end)
					]
				]);
				break;
			case 2:
				$title = '本周流量';
				list($begin, $end) = TimeExpand::getBeginAndEndTime(TimeExpand::WEEK);
				$args = VisitLogModel::getWeekStatus([
					'create_at' => [
						'between',
						TimeExpand::format($begin),
						TimeExpand::format($end)
					]
				]);
				break;
			case 1:
				$title = '昨日流量';
				$args = VisitLogModel::getHourStatus([
					'create_at' => [
						'between',
						TimeExpand::format(strtotime('-1 day'), 'Y-m-d 0:0:0'),
						TimeExpand::format(time(), 'Y-m-d 0:0:0')
					]
				]);
				break;
			case 0;
			default:
				$title = '今日流量';
				$args = VisitLogModel::getHourStatus([
					'create_at' => [
						'between',
						TimeExpand::format(time(), 'Y-m-d 0:0:0'),
						TimeExpand::format(strtotime('+1 day'), 'Y-m-d 0:0:0')
					]
				]);
				break;
		}
		list($count, $max) = $args;
		$this->ajaxReturn([
			'status' => 'success',
			'data' => $count,
			'max' => $max,
			'title' => $title
		]);
	}

}
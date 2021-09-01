<?php
declare(strict_types=1);
namespace Module\Exam\Domain\Repositories;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\QuestionModel;

final class StatisticsRepository {
    public static function subtotal() {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        $course_count = CourseModel::query()->count();
        $question_count = QuestionModel::query()->count();
        $question_today = QuestionModel::where('created_at', '>=', $todayStart)->count();
        $page_count = PageModel::query()->count();
        $page_today = PageModel::where('created_at', '>=', $todayStart)->count();
        $evaluate_count = PageEvaluateModel::query()->count();
        $evaluate_today = PageEvaluateModel::where('created_at', '>=', $todayStart)->count();
        return compact('course_count', 'question_count', 'question_today', 'page_count', 'page_today', 'evaluate_count', 'evaluate_today');
    }
}
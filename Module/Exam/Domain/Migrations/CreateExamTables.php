<?php
namespace Module\Exam\Domain\Migrations;


use Module\Exam\Domain\Entities\CourseEntity;
use Module\Exam\Domain\Entities\CourseLinkEntity;
use Module\Exam\Domain\Entities\PageEntity;
use Module\Exam\Domain\Entities\PageEvaluateEntity;
use Module\Exam\Domain\Entities\PageQuestionEntity;
use Module\Exam\Domain\Entities\QuestionAnswerEntity;
use Module\Exam\Domain\Entities\QuestionEntity;
use Module\Exam\Domain\Entities\QuestionOptionEntity;
use Module\Exam\Domain\Entities\QuestionWrongEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateExamTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        $this->append(CourseEntity::tableName(), function (Table $table) {
            $table->comment('科目');
            $table->id();
            $table->column('name')->varchar(30);
            $table->column('thumb')->varchar(200)->default('');
            $table->column('description')->varchar(200)->default('');
            $table->uint('parent_id')->default(0);
            $table->timestamps();
        })->append(CourseLinkEntity::tableName(), function (Table $table) {
            $table->comment('科目关联表');
            $table->uint('course_id');
            $table->uint('link_id');
            $table->column('title')->varchar(100)->default('');
        })->append(QuestionEntity::tableName(), function (Table $table) {
            $table->comment('题库');
            $table->id();
            $table->column('title')->varchar(255);
            $table->column('image')->varchar(200)->default('');
            $table->uint('course_id');
            $table->uint('parent_id')->default(0);
            $table->column('type')->tinyint(1)->default(0)->comment('题目类型');
            $table->column('easiness')->tinyint(1)->default(0)->comment('难易程度');
            $table->column('content')->text()->nullable()->comment('题目内容');
            $table->column('dynamic')->text()->nullable()->comment('动态内容');
            $table->column('answer')->text()->nullable()->comment('题目答案');
            $table->column('analysis')->text()->nullable()->comment('题库解析');
            $table->timestamps();
        })->append(QuestionOptionEntity::tableName(), function (Table $table) {
            $table->comment('题选择选项');
            $table->id();
            $table->column('content')->varchar(255);
            $table->uint('question_id');
            $table->column('type')->tinyint(1)->default(0);
            $table->column('is_right')->bool()->default(0)->comment('是否是正确答案');
        })->append(QuestionAnswerEntity::tableName(), function (Table $table) {
            $table->comment('用户回答');
            $table->id();
            $table->uint('question_id');
            $table->uint('user_id');
            $table->column('content')->text()->nullable()->comment('题目内容');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(QuestionWrongEntity::tableName(), function (Table $table) {
            $table->comment('错题集');
            $table->id();
            $table->uint('question_id');
            $table->uint('user_id');
            $table->short('frequency')->unsigned()
                ->default(1)->comment('出错次数');
            $table->timestamps();
        })->append(PageEntity::tableName(), function (Table $table) {
            $table->comment('试卷集');
            $table->id();
            $table->column('name')->varchar(200)->comment('试卷名');
            $table->column('rule_type')->tinyint()->comment('试卷生存类型');
            $table->column('rule_value')->varchar()->comment('试卷组成规则');
            $table->timestamp('start_at')->comment('开始时间');
            $table->timestamp('end_at')->comment('结束时间');
            $table->short('limit_time')->unsigned()->default(0)->comment('限时');
            $table->timestamps();
        })->append(PageQuestionEntity::tableName(), function (Table $table) {
            $table->comment('每次试卷题目及用户回答，如果为固定则复制开始的');
            $table->id();
            $table->uint('page_id');
            $table->uint('evaluate_id');
            $table->uint('question_id');
            $table->uint('user_id');
            $table->column('content')->text()->nullable()->comment('题目内容');
            $table->column('answer')->text()->nullable()->comment('用户回答');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(PageEvaluateEntity::tableName(), function (Table $table) {
            $table->comment('试卷评估结果');
            $table->id();
            $table->uint('page_id');
            $table->uint('user_id');
            $table->short('spent_time')->unsigned()->default(0);
            $table->short('right')->unsigned()->default(0);
            $table->short('wrong')->unsigned()->default(0);
            $table->short('score')->default(0);
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->column('remark')->varchar()->default('')->comment('评语');
            $table->timestamps();
        })->autoUp();
    }
}
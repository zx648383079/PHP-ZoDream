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
            $table->setComment('科目');
            $table->set('id')->pk(true);
            $table->set('name')->varchar(30)->notNull();
            $table->set('thumb')->varchar(200)->defaultVal('');
            $table->set('description')->varchar(200)->defaultVal('');
            $table->set('parent_id')->int(11, true)->defaultVal(0);
            $table->timestamps();
        })->append(CourseLinkEntity::tableName(), function (Table $table) {
            $table->setComment('科目关联表');
            $table->set('course_id')->int(11, true, true);
            $table->set('link_id')->int(11, true, true);
            $table->set('title')->varchar(100)->defaultVal('');
        })->append(QuestionEntity::tableName(), function (Table $table) {
            $table->setComment('题库');
            $table->set('id')->pk(true);
            $table->set('title')->varchar(255)->notNull();
            $table->set('image')->varchar(200)->defaultVal('');
            $table->set('course_id')->int(11, true, true);
            $table->set('parent_id')->int(11, true)->defaultVal(0);
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('题目类型');
            $table->set('easiness')->tinyint(1)->defaultVal(0)->comment('难易程度');
            $table->set('content')->text()->comment('题目内容');
            $table->set('dynamic')->text()->comment('动态内容');
            $table->set('answer')->text()->comment('题目答案');
            $table->set('analysis')->text()->comment('题库解析');
            $table->timestamps();
        })->append(QuestionOptionEntity::tableName(), function (Table $table) {
            $table->setComment('题选择选项');
            $table->set('id')->pk(true);
            $table->set('content')->varchar(255)->notNull();
            $table->set('question_id')->int(11, true, true);
            $table->set('type')->tinyint(1)->defaultVal(0);
            $table->set('is_right')->bool()->defaultVal(0)->comment('是否是正确答案');
        })->append(QuestionAnswerEntity::tableName(), function (Table $table) {
            $table->setComment('用户回答');
            $table->set('id')->pk(true);
            $table->set('question_id')->int(11, true, true);
            $table->set('user_id')->int(11, true, true);
            $table->set('content')->text()->comment('题目内容');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('状态');
            $table->timestamps();
        })->append(QuestionWrongEntity::tableName(), function (Table $table) {
            $table->setComment('错题集');
            $table->set('id')->pk(true);
            $table->set('question_id')->int(11, true, true);
            $table->set('user_id')->int(11, true, true);
            $table->set('frequency')->smallInt(5)
                ->defaultVal(1)->comment('出错次数');
            $table->timestamps();
        })->append(PageEntity::tableName(), function (Table $table) {
            $table->setComment('试卷集');
            $table->set('id')->pk(true);
            $table->set('name')->varchar(200)->notNull()->comment('试卷名');
            $table->set('rule_type')->tinyint()->notNull()->comment('试卷生存类型');
            $table->set('rule_value')->varchar()->notNull()->comment('试卷组成规则');
            $table->timestamp('start_at')->comment('开始时间');
            $table->timestamp('end_at')->comment('结束时间');
            $table->set('limit_time')->smallInt(5)->defaultVal(0)->comment('限时');
            $table->timestamps();
        })->append(PageQuestionEntity::tableName(), function (Table $table) {
            $table->setComment('每次试卷题目及用户回答，如果为固定则复制开始的');
            $table->set('id')->pk(true);
            $table->set('page_id')->int(11, true, true);
            $table->set('evaluate_id')->int(11, true, true);
            $table->set('question_id')->int(11, true, true);
            $table->set('user_id')->int(11, true, true);
            $table->set('content')->text()->comment('题目内容');
            $table->set('answer')->text()->comment('用户回答');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('状态');
            $table->timestamps();
        })->append(PageEvaluateEntity::tableName(), function (Table $table) {
            $table->setComment('试卷评估结果');
            $table->set('id')->pk(true);
            $table->set('page_id')->int(11, true, true);
            $table->set('user_id')->int(11, true, true);
            $table->set('spent_time')->smallInt(5)->defaultVal(0);
            $table->set('right')->smallInt(4)->defaultVal(0);
            $table->set('wrong')->smallInt(4)->defaultVal(0);
            $table->set('score')->smallInt(3)->defaultVal(0);
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('状态');
            $table->set('remark')->varchar()->defaultVal('')->comment('评语');
            $table->timestamps();
        });
        parent::up();
    }
}
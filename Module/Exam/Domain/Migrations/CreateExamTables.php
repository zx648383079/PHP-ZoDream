<?php
namespace Module\Exam\Domain\Migrations;


use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Exam\Domain\Entities\CourseEntity;
use Module\Exam\Domain\Entities\CourseGradeEntity;
use Module\Exam\Domain\Entities\PageEntity;
use Module\Exam\Domain\Entities\PageEvaluateEntity;
use Module\Exam\Domain\Entities\PageQuestionEntity;
use Module\Exam\Domain\Entities\QuestionAnalysisEntity;
use Module\Exam\Domain\Entities\QuestionAnswerEntity;
use Module\Exam\Domain\Entities\QuestionEntity;
use Module\Exam\Domain\Entities\QuestionMaterialEntity;
use Module\Exam\Domain\Entities\QuestionOptionEntity;
use Module\Exam\Domain\Entities\QuestionWrongEntity;
use Module\Exam\Domain\Entities\UpgradeEntity;
use Module\Exam\Domain\Entities\UpgradePathEntity;
use Module\Exam\Domain\Entities\UpgradeUserEntity;
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
            $table->string('name', 30);
            $table->string('thumb', 200)->default('');
            $table->string('description', 200)->default('');
            $table->uint('parent_id')->default(0);
            $table->timestamps();
        })->append(CourseGradeEntity::tableName(), function (Table $table) {
            $table->comment('科目等级别名表');
            $table->id();
            $table->uint('course_id')->default(0);
            $table->string('name', 30);
            $table->uint('grade', 5)->default(1);
        })->append(UpgradeEntity::tableName(), function (Table $table) {
            $table->comment('晋级名称表');
            $table->id();
            $table->uint('course_id')->comment('所属科目');
            $table->uint('course_grade', 5)->default(1);
            $table->string('name', 100);
            $table->string('icon', 100)->default('')->comment('勋章图标');
            $table->string('description')->default('');
            $table->timestamps();
        })->append(UpgradePathEntity::tableName(), function (Table $table) {
            $table->comment('晋级路线表');
            $table->id();
            $table->uint('item_type', 1)->default(0)->comment('');
            $table->uint('item_id');
            $table->uint('course_grade', 5)->default(1);
        })->append(UpgradeUserEntity::tableName(), function (Table $table) {
            $table->comment('用户晋级记录表');
            $table->id();
            $table->uint('upgrade_id');
            $table->uint('user_id');
            $table->timestamp('created_at');
        })->append(QuestionEntity::tableName(), function (Table $table) {
            $table->comment('题库');
            $table->id();
            $table->string('title');
            $table->string('image', 200)->default('');
            $table->uint('course_id');
            $table->uint('course_grade', 5)->default(1);
            $table->uint('parent_id')->default(0);
            $table->uint('material_id')->default(0)->comment('题目素材');
            $table->uint('type', 1)->default(0)->comment('题目类型');
            $table->uint('easiness', 1)->default(0)->comment('难易程度');
            $table->text('content')->nullable()->comment('题目内容');
            $table->text('dynamic')->nullable()->comment('动态内容');
            $table->text('answer')->nullable()->comment('题目答案');
            $table->uint('user_id');
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(QuestionOptionEntity::tableName(), function (Table $table) {
            $table->comment('题选择选项');
            $table->id();
            $table->string('content');
            $table->uint('question_id');
            $table->uint('type', 1)->default(0);
            $table->bool('is_right')->default(0)->comment('是否是正确答案');
        })->append(QuestionMaterialEntity::tableName(), function (Table $table) {
            $table->comment('题选素材库');
            $table->id();
            $table->uint('course_id');
            $table->string('title');
            $table->string('description')->default('');
            $table->uint('type', 1)->default(0);
            $table->text('content')->nullable();
        })->append(QuestionAnalysisEntity::tableName(), function (Table $table) {
            $table->comment('题选解析');
            $table->id();
            $table->uint('question_id');
            $table->uint('type', 1)->default(0);
            $table->text('content')->nullable();
        })->append(QuestionAnswerEntity::tableName(), function (Table $table) {
            $table->comment('用户回答');
            $table->id();
            $table->uint('question_id');
            $table->uint('user_id');
            $table->text('content')->nullable()->comment('题目内容');
            $table->uint('answer_type', 1)->default(0)->comment('回答类型/默认文字');
            $table->text('answer')->nullable()->comment('用户回答');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(QuestionWrongEntity::tableName(), function (Table $table) {
            $table->comment('错题集');
            $table->id();
            $table->uint('question_id');
            $table->uint('user_id');
            $table->uint('frequency', 5)
                ->default(1)->comment('出错次数');
            $table->timestamps();
        })->append(PageEntity::tableName(), function (Table $table) {
            $table->comment('试卷集');
            $table->id();
            $table->string('name', 200)->comment('试卷名');
            $table->uint('rule_type', 1)->default(0)->comment('试卷生存类型');
            $table->string('rule_value', 500)->default('')->comment('试卷组成规则');
            $table->timestamp('start_at')->comment('开始时间');
            $table->timestamp('end_at')->comment('结束时间');
            $table->uint('limit_time', 4)->default(0)->comment('限时');
            $table->short('score')->default(0)->comment('总分数');
            $table->short('question_count')->default(0)->comment('题目数');
            $table->uint('user_id');
            $table->uint('status', 1)->default(0);
            $table->uint('course_id')->default(0);
            $table->uint('course_grade', 5)->default(1);
            $table->timestamps();
        })->append(PageQuestionEntity::tableName(), function (Table $table) {
            $table->comment('每次试卷题目及用户回答，如果为固定则复制开始的');
            $table->id();
            $table->uint('page_id');
            $table->uint('evaluate_id');
            $table->uint('question_id');
            $table->uint('user_id');
            $table->short('max_score')->default(0)->comment('满分');
            $table->short('score')->default(0)->comment('获得的分数');
            $table->text('content')->nullable()->comment('题目内容');
            $table->uint('answer_type', 1)->default(0)->comment('回答类型/默认文字');
            $table->text('answer')->nullable()->comment('用户回答');
            $table->uint('status', 1)->default(0)->comment('状态');
            $table->timestamps();
        })->append(PageEvaluateEntity::tableName(), function (Table $table) {
            $table->comment('试卷评估结果');
            $table->id();
            $table->uint('page_id');
            $table->uint('user_id');
            $table->uint('spent_time', 4)->default(0);
            $table->uint('right', 4)->default(0)->comment('正确数量');
            $table->uint('wrong', 4)->default(0)->comment('错误数量');
            $table->short('score')->default(0);
            $table->uint('status', 1)->default(0)->comment('状态');
            $table->string('remark')->default('')->comment('评语');
            $table->timestamps();
        })->autoUp();
    }

    public function seed()
    {
        RoleRepository::newPermission([
            'exam_manage' => '题库管理'
        ]);
        $this->insertGrade();
    }

    private function insertGrade() {
        if (CourseGradeEntity::where('course_id', 0)->count() > 0) {
            return;
        }
        $items = [
            '小学',
            '初中',
            '高中',
            '大学',
        ];
        $data = [];
        foreach ($items as $i => $name) {
            $data[] = [
                'name' => $name,
                'grade' => $i + 1,
            ];
        }
        CourseGradeEntity::query()->insert($data);
    }
}
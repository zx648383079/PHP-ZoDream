<?php
namespace Module\Career\Domain\Migrations;

use Module\Career\Domain\Entities\CategoryEntity;
use Module\Career\Domain\Entities\CompanyEntity;
use Module\Career\Domain\Entities\CompanyHrEntity;
use Module\Career\Domain\Entities\EducationalExperienceEntity;
use Module\Career\Domain\Entities\InfluenceEntity;
use Module\Career\Domain\Entities\InterviewEntity;
use Module\Career\Domain\Entities\InterviewLogEntity;
use Module\Career\Domain\Entities\JobEntity;
use Module\Career\Domain\Entities\JobExpectationsEntity;
use Module\Career\Domain\Entities\JobLogEntity;
use Module\Career\Domain\Entities\PortfolioEntity;
use Module\Career\Domain\Entities\ProfileEntity;
use Module\Career\Domain\Entities\SkillEntity;
use Module\Career\Domain\Entities\WorkExperienceEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;


class CreateCareerTables extends Migration {

    public function up() {
        $this->append(CategoryEntity::tableName(), function(Table $table) {
            $table->id();
            $table->string('name', 20);
            $table->uint('parent_id')->default(0);
            $table->string('description')->default('');
            $table->timestamps();
        })->append(ProfileEntity::tableName(), function(Table $table) {
            $table->id()->comment('使用user.id');
            $table->string('name', 20);
            $table->string('avatar');
            $table->uint('region_id')->default(0);
            $table->uint('cat_id')->default(0);
            $table->uint('status', 2)->default(0);
            $table->decimal('salary', 10, 2)->default(0)->comment('月薪');
            $table->uint('salary_type', 1)->default(0)->comment('工资方式');
            $table->string('remark')->nullable()->comment('个人介绍');
            $table->string('longitude', 50)->default('')->comment('经度');
            $table->string('latitude', 50)->default('')->comment('纬度');
            $table->timestamps();
        })->append(WorkExperienceEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('company', 200);
            $table->string('post')->comment('职位');
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->string('remark')->nullable()->comment('经验描述');
            $table->string('certificate')->nullable()->comment('工作证明');
            $table->timestamps();
        })->append(EducationalExperienceEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('school', 200);
            $table->string('major')->comment('专业');
            $table->uint('education', 1)->comment('学历');
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->string('remark')->nullable()->comment('经验描述');
            $table->string('certificate')->nullable()->comment('学历证明');
            $table->timestamps();
        })->append(SkillEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->uint('score', 1)->default(100)->comment('自我评分%');
            $table->timestamps();
        })->append(PortfolioEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->string('link', 200);
            $table->string('trade', 200)->comment('行业');
            $table->string('function')->nullable()->comment('功能');
            $table->string('remark')->nullable()->comment('描述');
            $table->string('duty')->nullable()->comment('职责');
            $table->string('images')->nullable()->comment('作品图片');
            $table->timestamps();
        })->append(InfluenceEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->string('link', 200);
            $table->timestamps();
        })->append(JobExpectationsEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('cat_id');
            $table->decimal('salary', 10, 2)->default(0)->comment('月薪');
            $table->uint('salary_type', 1)->default(0)->comment('工资方式');
            $table->uint('region_id')->default(0)->comment('城市');
            $table->timestamps();
        })->append(CompanyEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->string('name');
            $table->string('address')->comment('地址');
            $table->uint('region_id')->default(0)->comment('城市');
            $table->string('description')->default('')->comment('介绍');
            $table->uint('credit', 1)->comment('信用评分');
            $table->uint('type', 1)->default(0)->comment('公司类型');
            $table->uint('employee_count', 1)->default(0)->comment('雇员数');
            $table->timestamps();
        })->append(CompanyHrEntity::tableName(), function(Table $table) {
            $table->id()->comment('user.id');
            $table->string('name');
            $table->string('avatar');
            $table->string('address')->comment('地址');
            $table->uint('region_id')->default(0)->comment('城市');
            $table->string('description')->default('')->comment('介绍');
            $table->uint('credit', 1)->default(5)->comment('信用评分');
            $table->string('longitude', 50)->default('')->comment('经度');
            $table->string('latitude', 50)->default('')->comment('纬度');
            $table->timestamps();
        })->append(JobEntity::tableName(), function(Table $table) {
            $table->id();
            $table->string('name');
            $table->uint('company_id');
            $table->uint('user_id');
            $table->string('address')->comment('地址');
            $table->uint('region_id')->default(0)->comment('城市');
            $table->string('description')->default('')->comment('介绍');
            $table->decimal('min_salary', 10, 2)->default(0)->comment('月薪');
            $table->decimal('max_salary', 10, 2)->default(0)->comment('月薪');
            $table->uint('salary_type', 1)->default(0)->comment('工资方式');
            $table->string('longitude', 50)->default('')->comment('经度');
            $table->string('latitude', 50)->default('')->comment('纬度');
            $table->string('content')->nullable();
            $table->uint('education', 1)->default(0)->comment('学历');
            $table->uint('work_time', 1)->default(0)->comment('工作经验');
            $table->string('tags')->default('')->comment('标签');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(JobLogEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('job_id');
            $table->uint('user_id');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(InterviewEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('job_id');
            $table->uint('company_id');
            $table->uint('hr_id');
            $table->uint('user_id');
            $table->string('address');
            $table->timestamp('interview_at')->comment('面试时间');
            $table->timestamp('end_at')->comment('面试通知截止时间');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(InterviewLogEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('interview_id');
            $table->uint('user_id');
            $table->uint('type', 1)->default(0);
            $table->string('data')->default('')->comment('附加数据');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->autoUp();
    }

}
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
            $table->column('name')->varchar(20);
            $table->uint('parent_id')->default(0);
            $table->column('description')->varchar()->default('');
            $table->timestamps();
        })->append(ProfileEntity::tableName(), function(Table $table) {
            $table->id()->comment('使用user.id');
            $table->column('name')->varchar(20);
            $table->column('avatar')->varchar();
            $table->uint('region_id')->default(0);
            $table->uint('cat_id')->default(0);
            $table->uint('status', 2)->default(0);
            $table->column('salary')->decimal(10, 2)->default(0)->comment('月薪');
            $table->column('salary_type')->tinyint(1)->default(0)->comment('工资方式');
            $table->column('remark')->text()->nullable()->comment('个人介绍');
            $table->column('longitude')->varchar(50)->default('')->comment('经度');
            $table->column('latitude')->varchar(50)->default('')->comment('纬度');
            $table->timestamps();
        })->append(WorkExperienceEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('company')->varchar(200);
            $table->column('post')->varchar()->comment('职位');
            $table->column('start_at')->date()->nullable();
            $table->column('end_at')->date()->nullable();
            $table->column('remark')->text()->nullable()->comment('经验描述');
            $table->column('certificate')->varchar()->nullable()->comment('工作证明');
            $table->timestamps();
        })->append(EducationalExperienceEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('school')->varchar(200);
            $table->column('major')->varchar()->comment('专业');
            $table->column('education')->tinyint(1)->comment('学历');
            $table->column('start_at')->date()->nullable();
            $table->column('end_at')->date()->nullable();
            $table->column('remark')->text()->nullable()->comment('经验描述');
            $table->column('certificate')->varchar()->nullable()->comment('学历证明');
            $table->timestamps();
        })->append(SkillEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(200);
            $table->column('score')->tinyint(1)->default(100)->comment('自我评分%');
            $table->timestamps();
        })->append(PortfolioEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(200);
            $table->column('link')->varchar(200);
            $table->column('trade')->varchar(200)->comment('行业');
            $table->column('function')->varchar()->nullable()->comment('功能');
            $table->column('remark')->varchar()->nullable()->comment('描述');
            $table->column('duty')->varchar()->nullable()->comment('职责');
            $table->column('images')->varchar()->nullable()->comment('作品图片');
            $table->timestamps();
        })->append(InfluenceEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar(200);
            $table->column('link')->varchar(200);
            $table->timestamps();
        })->append(JobExpectationsEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->uint('cat_id');
            $table->column('salary')->decimal(10, 2)->default(0)->comment('月薪');
            $table->column('salary_type')->tinyint(1)->default(0)->comment('工资方式');
            $table->column('region_id')->int()->default(0)->comment('城市');
            $table->timestamps();
        })->append(CompanyEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('cat_id');
            $table->column('name')->varchar();
            $table->column('address')->varchar()->comment('地址');
            $table->column('region_id')->int()->default(0)->comment('城市');
            $table->column('description')->varchar()->default('')->comment('介绍');
            $table->column('credit')->tinyint(1)->comment('信用评分');
            $table->column('type')->tinyint(1)->default(0)->comment('公司类型');
            $table->column('employee_count')->tinyint(1)->default(0)->comment('雇员数');
            $table->timestamps();
        })->append(CompanyHrEntity::tableName(), function(Table $table) {
            $table->id()->comment('user.id');
            $table->column('name')->varchar();
            $table->column('avatar')->varchar();
            $table->column('address')->varchar()->comment('地址');
            $table->column('region_id')->int()->default(0)->comment('城市');
            $table->column('description')->varchar()->default('')->comment('介绍');
            $table->column('credit')->tinyint(1)->default(5)->comment('信用评分');
            $table->column('longitude')->varchar(50)->default('')->comment('经度');
            $table->column('latitude')->varchar(50)->default('')->comment('纬度');
            $table->timestamps();
        })->append(JobEntity::tableName(), function(Table $table) {
            $table->id();
            $table->column('name')->varchar()->notNull();
            $table->uint('company_id');
            $table->uint('user_id');
            $table->column('address')->varchar()->comment('地址');
            $table->uint('region_id')->default(0)->comment('城市');
            $table->column('description')->varchar()->default('')->comment('介绍');
            $table->column('min_salary')->decimal(10, 2)->default(0)->comment('月薪');
            $table->column('max_salary')->decimal(10, 2)->default(0)->comment('月薪');
            $table->column('salary_type')->tinyint(1)->default(0)->comment('工资方式');
            $table->column('longitude')->varchar(50)->default('')->comment('经度');
            $table->column('latitude')->varchar(50)->default('')->comment('纬度');
            $table->column('content')->text()->nullable();
            $table->column('education')->tinyint(1)->default(0)->comment('学历');
            $table->column('work_time')->tinyint(1)->default(0)->comment('工作经验');
            $table->column('tags')->varchar()->default('')->comment('标签');
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
            $table->column('address')->varchar();
            $table->timestamp('interview_at')->comment('面试时间');
            $table->timestamp('end_at')->comment('面试通知截止时间');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(InterviewLogEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('interview_id');
            $table->uint('user_id');
            $table->column('type')->tinyint(1)->default(0);
            $table->column('data')->varchar()->default('')->comment('附加数据');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->autoUp();
    }

}
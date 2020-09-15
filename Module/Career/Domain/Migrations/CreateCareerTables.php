<?php
namespace Module\Career\Domain\Migrations;

use Module\Career\Domain\Entities\CategoryEntity;
use Module\Career\Domain\Entities\CompanyEntity;
use Module\Career\Domain\Entities\CompanyHrEntity;
use Module\Career\Domain\Entities\EducationalExperienceEntity;
use Module\Career\Domain\Entities\InfluenceEntity;
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
            $table->set('id')->pk(true);
            $table->set('name')->varchar(20)->notNull();
            $table->set('parent_id')->int()->defaultVal(0);
            $table->set('description')->varchar()->defaultVal('');
            $table->timestamps();
        })->append(ProfileEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true)->comment('使用user.id');
            $table->set('name')->varchar(20)->notNull();
            $table->set('avatar')->varchar()->notNull();
            $table->set('region_id')->int()->defaultVal(0);
            $table->set('cat_id')->int()->defaultVal(0);
            $table->set('status')->tinyint(1)->defaultVal(0);
            $table->set('salary')->decimal(10, 2)->defaultVal(0)->comment('月薪');
            $table->set('salary_type')->tinyint(1)->defaultVal(0)->comment('工资方式');
            $table->set('remark')->text()->comment('个人介绍');
            $table->set('longitude')->varchar(50)->comment('经度');
            $table->set('latitude')->varchar(50)->comment('纬度');
            $table->timestamps();
        })->append(WorkExperienceEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('company')->varchar(200)->notNull();
            $table->set('post')->varchar()->notNull()->comment('职位');
            $table->set('start_at')->date();
            $table->set('end_at')->date();
            $table->set('remark')->text()->comment('经验描述');
            $table->set('certificate')->varchar()->comment('工作证明');
            $table->timestamps();
        })->append(EducationalExperienceEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('school')->varchar(200)->notNull();
            $table->set('major')->varchar()->notNull()->comment('专业');
            $table->set('education')->tinyint(1)->comment('学历');
            $table->set('start_at')->date();
            $table->set('end_at')->date();
            $table->set('remark')->text()->comment('经验描述');
            $table->set('certificate')->varchar()->comment('学历证明');
            $table->timestamps();
        })->append(SkillEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(200)->notNull();
            $table->set('score')->tinyint(1)->defaultVal(100)->comment('自我评分%');
            $table->timestamps();
        })->append(PortfolioEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(200)->notNull();
            $table->set('link')->varchar(200)->notNull();
            $table->set('trade')->varchar(200)->notNull()->comment('行业');
            $table->set('function')->varchar()->comment('功能');
            $table->set('remark')->varchar()->comment('描述');
            $table->set('duty')->varchar()->comment('职责');
            $table->set('images')->varchar()->comment('作品图片');
            $table->timestamps();
        })->append(InfluenceEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('name')->varchar(200)->notNull();
            $table->set('link')->varchar(200)->notNull();
            $table->timestamps();
        })->append(JobExpectationsEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull();
            $table->set('cat_id')->int()->notNull();
            $table->set('salary')->decimal(10, 2)->defaultVal(0)->comment('月薪');
            $table->set('salary_type')->tinyint(1)->defaultVal(0)->comment('工资方式');
            $table->set('region_id')->int()->defaultVal(0)->comment('城市');
            $table->timestamps();
        })->append(CompanyEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('cat_id')->int()->notNull();
            $table->set('name')->varchar()->notNull();
            $table->set('address')->varchar()->notNull()->comment('地址');
            $table->set('region_id')->int()->defaultVal(0)->comment('城市');
            $table->set('description')->varchar()->defaultVal('')->comment('介绍');
            $table->set('credit')->tinyint(1)->comment('信用评分');
            $table->set('type')->tinyint(1)->defaultVal(0)->comment('公司类型');
            $table->set('employee_count')->tinyint(1)->defaultVal(0)->comment('雇员数');
            $table->timestamps();
        })->append(CompanyHrEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true)->comment('user.id');
            $table->set('name')->varchar()->notNull();
            $table->set('avatar')->varchar()->notNull();
            $table->set('address')->varchar()->notNull()->comment('地址');
            $table->set('region_id')->int()->defaultVal(0)->comment('城市');
            $table->set('description')->varchar()->defaultVal('')->comment('介绍');
            $table->set('credit')->tinyint(1)->comment('信用评分');
            $table->set('longitude')->varchar(50)->comment('经度');
            $table->set('latitude')->varchar(50)->comment('纬度');
            $table->timestamps();
        })->append(JobEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('name')->varchar()->notNull();
            $table->set('company_id')->int()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('address')->varchar()->notNull()->comment('地址');
            $table->set('region_id')->int()->defaultVal(0)->comment('城市');
            $table->set('description')->varchar()->defaultVal('')->comment('介绍');
            $table->set('min_salary')->decimal(10, 2)->defaultVal(0)->comment('月薪');
            $table->set('max_salary')->decimal(10, 2)->defaultVal(0)->comment('月薪');
            $table->set('salary_type')->tinyint(1)->defaultVal(0)->comment('工资方式');
            $table->set('longitude')->varchar(50)->comment('经度');
            $table->set('latitude')->varchar(50)->comment('纬度');
            $table->set('content')->text();
            $table->set('education')->tinyint(1)->defaultVal(0)->comment('学历');
            $table->set('work_time')->tinyint(1)->defaultVal(0)->comment('工作经验');
            $table->set('tags')->varchar()->defaultVal('')->comment('标签');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('状态');
            $table->timestamps();
        })->append(JobLogEntity::tableName(), function(Table $table) {
            $table->set('id')->pk(true);
            $table->set('job_id')->varchar()->notNull();
            $table->set('user_id')->int()->notNull();
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('状态');
            $table->timestamps();
        })->autoUp();
    }

}
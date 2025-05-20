<?php
declare(strict_types=1);
namespace Module\Career\Domain\Migrations;

use Module\Career\Domain\Entities\AwardEntity;
use Module\Career\Domain\Entities\CertificateEntity;
use Module\Career\Domain\Entities\IndustryEntity;
use Module\Career\Domain\Entities\CompanyEntity;
use Module\Career\Domain\Entities\CompanyHrEntity;
use Module\Career\Domain\Entities\EducationalExperienceEntity;
use Module\Career\Domain\Entities\InfluenceEntity;
use Module\Career\Domain\Entities\InterviewEntity;
use Module\Career\Domain\Entities\InterviewLogEntity;
use Module\Career\Domain\Entities\JobEntity;
use Module\Career\Domain\Entities\ResumeEntity;
use Module\Career\Domain\Entities\JobLogEntity;
use Module\Career\Domain\Entities\PortfolioEntity;
use Module\Career\Domain\Entities\PositionEntity;
use Module\Career\Domain\Entities\ProfileEntity;
use Module\Career\Domain\Entities\ResumePositionEntity;
use Module\Career\Domain\Entities\ResumeRegionEntity;
use Module\Career\Domain\Entities\SkillEntity;
use Module\Career\Domain\Entities\WorkExperienceEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateCareerTables extends Migration {

    public function up(): void {
        $this->append(PositionEntity::tableName(), function(Table $table) {
            $table->comment('职位');
            $table->id();
            $table->string('name', 20);
            $table->uint('parent_id')->default(0);
            $table->string('description')->default('');
        })->append(IndustryEntity::tableName(), function(Table $table) {
            $table->comment('行业');
            $table->id();
            $table->string('name', 20);
        })->append(ProfileEntity::tableName(), function(Table $table) {
            $table->comment('求职者基本信息');
            $table->id()->comment('使用user.id');
            $table->string('name', 20);
            $table->string('avatar');
            $table->uint('region_id')->default(0);
            $table->uint('position_id')->default(0);
            $table->uint('status', 2)->default(0);
            $table->decimal('salary', 10, 2)->default(0)->comment('月薪');
            $table->uint('salary_rule', 1)->default(0)->comment('工资方式');
            $table->string('remark')->default('')->comment('个人介绍');
            $table->string('longitude', 50)->default('')->comment('经度');
            $table->string('latitude', 50)->default('')->comment('纬度');
            $table->timestamps();
        })->append(WorkExperienceEntity::tableName(), function(Table $table) {
            $table->comment('工作经历');
            $table->id();
            $table->uint('user_id');
            $table->string('company', 200);
            $table->string('position')->comment('职位');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->string('remark')->default('')->comment('经验描述');
            $table->string('certificate')->default('')->comment('工作证明');
            $table->timestamps();
        })->append(EducationalExperienceEntity::tableName(), function(Table $table) {
            $table->comment('教育经历');
            $table->id();
            $table->uint('user_id');
            $table->string('school', 200);
            $table->string('major')->comment('专业');
            $table->uint('education', 1)->comment('学历');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->string('remark')->default('')->comment('经验描述');
            $table->string('certificate')->default('')->comment('学历证明');
            $table->timestamps();
        })->append(SkillEntity::tableName(), function(Table $table) {
            $table->comment('个人技能');
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->uint('score', 1)->default(100)->comment('自我评分%');
            $table->timestamps();
        })->append(AwardEntity::tableName(), function(Table $table) {
            $table->comment('个人获得的奖项');
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->string('remark')->default('')->comment('奖项描述');
            $table->timestamp('got_at')->comment('获得的时间');
            $table->timestamps();
        })->append(CertificateEntity::tableName(), function(Table $table) {
            $table->comment('个人获得的证书');
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->string('remark')->default('')->comment('奖项描述');
            $table->bool('is_got')->default(0)->comment('是否获得');
            $table->timestamp('got_at')->comment('获得的时间');
            $table->timestamps();
        })->append(PortfolioEntity::tableName(), function(Table $table) {
            $table->comment('项目作品');
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->string('link', 200);
            $table->string('trade', 200)->comment('行业');
            $table->string('function')->default('')->comment('功能');
            $table->string('remark')->default('')->comment('描述');
            $table->string('duty')->default('')->comment('职责');
            $table->string('images')->default('')->comment('作品图片');
            $table->timestamps();
        })->append(InfluenceEntity::tableName(), function(Table $table) {
            $table->comment('社区影响力');
            $table->id();
            $table->uint('user_id');
            $table->string('name', 200);
            $table->string('link', 200);
            $table->timestamps();
        })->append(ResumeEntity::tableName(), function(Table $table) {
            $table->comment('求职期望/建立');
            $table->id();
            $table->uint('user_id');
            $table->decimal('salary', 10, 2)->default(0)->comment('月薪');
            $table->uint('salary_rule', 1)->default(0)->comment('工资方式');
            $table->uint('job_type', 1)->default(0)->comment('工作方式：全职/兼职/实习');
            $table->bool('on_anytime')->default(0)->comment('是否随时到岗');
            $table->timestamp('work_date')->comment('到岗日期');
            $table->uint('weekly_days', 1)->default(0)->comment('一周工作天数');
            $table->uint('employ_period', 1)->default(0)->comment('工作期限/月');
            $table->timestamps();
        })->append(ResumePositionEntity::tableName(), function(Table $table) {
            $table->comment('求职岗位');
            $table->uint('resume_id');
            $table->uint('position_id');
        })->append(ResumeRegionEntity::tableName(), function(Table $table) {
            $table->comment('求职岗位');
            $table->uint('resume_id');
            $table->uint('region_id');
        })->append(CompanyEntity::tableName(), function(Table $table) {
            $table->comment('公司');
            $table->id();
            $table->uint('industry_id');
            $table->string('name');
            $table->string('address')->comment('地址');
            $table->uint('region_id')->default(0)->comment('城市');
            $table->string('description')->default('')->comment('介绍');
            $table->uint('credit', 1)->comment('信用评分');
            $table->uint('type', 1)->default(0)->comment('公司类型');
            $table->uint('employee_count', 1)->default(0)->comment('雇员数');
            $table->uint('financing_stage', 1)->default(0)->comment('融资阶段');
            $table->timestamps();
        })->append(CompanyHrEntity::tableName(), function(Table $table) {
            $table->comment('公司HR');
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
            $table->comment('工作职位');
            $table->id();
            $table->string('name');
            $table->uint('company_id');
            $table->uint('user_id');
            $table->uint('type', 1)->default(0)->comment('工作类型：全职/兼职/实习');
            $table->string('address')->comment('地址');
            $table->uint('region_id')->default(0)->comment('城市');
            $table->string('description')->default('')->comment('介绍');
            $table->decimal('min_salary', 10, 2)->default(0)->comment('日/月/年薪');
            $table->decimal('max_salary', 10, 2)->default(0)->comment('日/月/年薪');
            $table->uint('salary_rule', 1)->default(0)->comment('工资日/月/年薪');
            $table->uint('salary_type', 1)->default(0)->comment('工资类别：12/13/14薪');
            $table->string('longitude', 50)->default('')->comment('经度');
            $table->string('latitude', 50)->default('')->comment('纬度');
            $table->string('content')->nullable();
            $table->uint('education', 1)->default(0)->comment('学历');
            $table->uint('work_time', 1)->default(0)->comment('工作经验');
            $table->string('tags')->default('')->comment('标签');
            $table->uint('top_type', 1)->default(0)->comment('推荐类型/急招/限招/枪手');
            $table->uint('weekly_days', 1)->default(0)->comment('一周工作天数');
            $table->uint('check_period', 1)->default(0)->comment('试用期/月');
            $table->uint('employ_period', 1)->default(0)->comment('工作期限/月');
            $table->uint('head_count', 1)->default(0)->comment('限招数量');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(JobLogEntity::tableName(), function(Table $table) {
            $table->comment('工作申请记录');
            $table->id();
            $table->uint('job_id');
            $table->uint('user_id');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(InterviewEntity::tableName(), function(Table $table) {
            $table->comment('面试');
            $table->id();
            $table->uint('job_id');
            $table->uint('company_id');
            $table->uint('hr_id');
            $table->uint('user_id');
            $table->string('address');
            $table->timestamp('interview_at')->comment('下次面试时间');
            $table->timestamp('end_at')->comment('面试通知截止时间');
            $table->uint('status', 2)->default(0)->comment('状态');
            $table->timestamps();
        })->append(InterviewLogEntity::tableName(), function(Table $table) {
            $table->comment('面试记录');
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
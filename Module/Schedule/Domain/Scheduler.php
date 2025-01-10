<?php
namespace Module\Schedule\Domain;

use DateTime;
use Exception;
use InvalidArgumentException;

class Scheduler {
    /**
     * The queued jobs.
     *
     * @var array
     */
    private array $jobs = [];
    /**
     * Successfully executed jobs.
     *
     * @var array
     */
    private array $executedJobs = [];
    /**
     * Failed jobs.
     *
     * @var array
     */
    private array $failedJobs = [];
    /**
     * The verbose output of the scheduled jobs.
     *
     * @var array
     */
    private array $outputSchedule = [];
    /**
     * Create new instance.
     *
     * @param  array  $config
     */
    public function __construct(
        private readonly array $config = [])
    {
    }
    /**
     * Queue a job for execution in the correct queue.
     *
     * @param  Job  $job
     * @return void
     */
    private function queueJob(Job $job): void {
        $this->jobs[] = $job;
    }
    /**
     * Prioritise jobs in background.
     *
     * @return array
     */
    private function prioritiseJobs(): array {
        $background = [];
        $foreground = [];
        foreach ($this->jobs as $job) {
            if ($job->canRunInBackground()) {
                $background[] = $job;
            } else {
                $foreground[] = $job;
            }
        }
        return array_merge($background, $foreground);
    }
    /**
     * Get the queued jobs.
     *
     * @return Job[]
     */
    public function getQueuedJobs(): array
    {
        return $this->prioritiseJobs();
    }
    /**
     * Queues a function execution.
     *
     * @param  callable  $fn  The function to execute
     * @param  array  $args  Optional arguments to pass to the php script
     * @param  string  $id   Optional custom identifier
     * @return Job
     */
    public function call(callable $fn, array $args = [], string $id = '')
    {
        $job = new Job($fn, $args, $id);
        $this->queueJob($job->configure($this->config));
        return $job;
    }
    /**
     * Queues a php script execution.
     *
     * @param  string  $script  The path to the php script to execute
     * @param  string  $bin     Optional path to the php binary
     * @param  array  $args     Optional arguments to pass to the php script
     * @param  string  $id      Optional custom identifier
     * @return Job
     */
    public function php(string $script, string $bin = '', array $args = [], string $id = '') {
        $bin = !empty($bin) && file_exists($bin) ?
            $bin : (PHP_BINARY === '' ? '/usr/bin/php' : PHP_BINARY);
        $job = new Job($bin . ' ' . $script, $args, $id);
        if (! file_exists($script)) {
            $this->pushFailedJob(
                $job,
                new InvalidArgumentException('The script should be a valid path to a file.')
            );
        }
        $this->queueJob($job->configure($this->config));
        return $job;
    }

    /**
     * 执行php 语句
     * @param string $script
     * @param string $bin
     * @param array $args
     * @param string $id
     * @return Job
     */
    public function phpRaw(string $script, string $bin = '', array $args = [], string $id = '') {
        return $this->php(sprintf('-r %s', escapeshellarg($script)), $bin, $args, $id);
    }
    /**
     * Queue a raw shell command.
     *
     * @param  string  $command  The command to execute
     * @param  array  $args      Optional arguments to pass to the command
     * @param  string  $id       Optional custom identifier
     * @return Job
     */
    public function raw(string $command, array $args = [], string $id = '') {
        $job = new Job($command, $args, $id);
        $this->queueJob($job->configure($this->config));
        return $job;
    }

    /**
     * Run the scheduler.
     *
     * @param DateTime|null $runTime Optional, run at specific moment
     * @return array  Executed jobs
     */
    public function run(Datetime|null $runTime = null) {
        $jobs = $this->getQueuedJobs();
        if (is_null($runTime)) {
            $runTime = new DateTime('now');
        }
        foreach ($jobs as $job) {
            if ($job->isDue($runTime)) {
                try {
                    $job->run();
                    $this->pushExecutedJob($job);
                } catch (\Exception $e) {
                    $this->pushFailedJob($job, $e);
                }
            }
        }
        return $this->getExecutedJobs();
    }
    /**
     * Reset all collected data of last run.
     *
     * Call before run() if you call run() multiple times.
     */
    public function resetRun() {
        // Reset collected data of last run
        $this->executedJobs = [];
        $this->failedJobs = [];
        $this->outputSchedule = [];
        return $this;
    }
    /**
     * Add an entry to the scheduler verbose output array.
     *
     * @param  string  $string
     * @return void
     */
    private function addSchedulerVerboseOutput(string $string) {
        $now = '[' . (new DateTime('now'))->format('c') . '] ';
        $this->outputSchedule[] = $now . $string;
        // Print to stdoutput in light gray
        // echo "\033[37m{$string}\033[0m\n";
    }
    /**
     * Push a succesfully executed job.
     *
     * @param  Job  $job
     * @return Job
     */
    private function pushExecutedJob(Job $job)
    {
        $this->executedJobs[] = $job;
        $compiled = $job->compile();
        // If callable, log the string Closure
        if (is_callable($compiled)) {
            $compiled = 'Closure';
        }
        $this->addSchedulerVerboseOutput("Executing {$compiled}");
        return $job;
    }
    /**
     * Get the executed jobs.
     *
     * @return array
     */
    public function getExecutedJobs() {
        return $this->executedJobs;
    }
    /**
     * Push a failed job.
     *
     * @param  Job  $job
     * @param  Exception  $e
     * @return Job
     */
    private function pushFailedJob(Job $job, Exception $e) {
        $this->failedJobs[] = $job;
        $compiled = $job->compile();
        // If callable, log the string Closure
        if (is_callable($compiled)) {
            $compiled = 'Closure';
        }
        $this->addSchedulerVerboseOutput("{$e->getMessage()}: {$compiled}");
        return $job;
    }
    /**
     * Get the failed jobs.
     *
     * @return array
     */
    public function getFailedJobs(): array {
        return $this->failedJobs;
    }
    /**
     * Get the scheduler verbose output.
     *
     * @param  string  $type  Allowed: text, html, array
     * @return mixed  The return depends on the requested $type
     */
    public function getVerboseOutput(string $type = 'text'): string {
        return match ($type) {
            'text' => implode("\n", $this->outputSchedule),
            'html' => implode('<br>', $this->outputSchedule),
            'array' => $this->outputSchedule,
            default => throw new InvalidArgumentException('Invalid output type'),
        };
    }
    /**
     * Remove all queued Jobs.
     */
    public function clearJobs() {
        $this->jobs = [];
        return $this;
    }
}
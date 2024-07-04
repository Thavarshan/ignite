<?php

namespace App\Models\Traits;

use App\Models\JobState;
use Illuminate\Queue\InteractsWithQueue;

trait Stateful
{
    use InteractsWithQueue;

    /**
     * The state of the job.
     *
     * @var array
     */
    protected ?JobState $state = null;

    /**
     * Get the state of the job.
     *
     * @return array
     */
    public function state(): JobState
    {
        return $this->state ??= JobState::firstOrCreate([
            'job_id' => $this->getJobId(),
        ]);
    }

    /**
     * Get the job ID.
     *
     * @return string
     */
    public function getJobId(): string
    {
        if (isset($this->jobId)) {
            return $this->jobId;
        }

        return $this->job->getJobId();
    }
}

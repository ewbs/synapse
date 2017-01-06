<?php

namespace Barryvdh\Queue\Jobs;

use Barryvdh\Queue\Models\Job;
use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\SyncJob;

class AsyncJob extends SyncJob
{
    /**
     * The job model.
     *
     * @var Job
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Container\Container $container
     * @param \Barryvdh\Queue\Models\Job      $job
     *
     * @return void
     */
    public function __construct(Container $container, Job $job)
    {
        $this->job = $job;
        $this->container = $container;
        $this->job->retries = $this->job->retries + 1;
    }

    /**
     * Fire the job.
     *
     * @return void
     */
    public function fire()
    {
        // Get the payload from the job
        $payload = $this->parsePayload($this->job->payload);
        if(isset($payload['error'])) unset($payload['error']);

        // If we have to wait, sleep until our time has come
        if ($this->job->delay) {
            $this->job->status = Job::STATUS_WAITING;
            $this->job->save();
            sleep($this->job->delay);
        }

        // Mark job as started
        $this->job->status = Job::STATUS_STARTED;
        $this->job->save();
        try {
        // Fire the actual job
        $this->resolveAndFire($payload);

        // If job is not deleted, mark as finished
        if (!$this->deleted) {
            $this->job->status = Job::STATUS_FINISHED;
            $this->job->save();
        }
      }
      catch (\Exception $e){
      	\Log::error('Error when fire a job :'.$e->getMessage()."\nJob details : $this->job->payload");
      	$payload['error']=utf8_encode($e->__toString());
      	$this->job->payload=json_encode($payload);
      	$this->job->save();
      }
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();
        $this->job->delete();
    }

    /**
     * Parse the payload to an array.
     *
     * @param string $payload
     *
     * @return array|null
     */
    protected function parsePayload($payload)
    {
        return json_decode($payload, true);
    }
    
    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
    	return (int) $this->job->retries;
    }
    
    /**
     * Retourner le nom de la queue
     * 
     * {@inheritDoc}
     * @see \Illuminate\Queue\Jobs\Job::getQueue()
     */
    public function getQueue()
    {
    	return $this->job->queue;
    }
    
    /**
     * Rendre le payload à l'appel de cette méthode, utile pour le log en cas de failed_job.
     * {@inheritDoc}
     * @see \Illuminate\Queue\Jobs\SyncJob::getRawBody()
     */
    public function getRawBody()
    {
    	return $this->job->payload;
    }
}

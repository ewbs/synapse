<?php
namespace Barryvdh\Queue;

use Barryvdh\Queue\Models\Job;
use Illuminate\Queue\SyncQueue;
use Barryvdh\Queue\Jobs\AsyncJob;

class AsyncQueue extends SyncQueue
{
    /** @var array */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Push a new job onto the queue.
     *
     * @param string      $job
     * @param mixed       $data
     * @param string|null $queue
     *
     * @return int
     */
    public function push($job, $data='', $queue=null)
    {
        $id = $this->storeJob($job, $data, $queue);
        //$this->startProcess($id, 0);
        return 0;
    }

    /**
     * Store the job in the database.
     *
     * Returns the id of the job.
     *
     * @param string $job
     * @param mixed  $data
     * @param string|null $queue
     * @param int    $delay
     *
     * @return int
     */
    private function storeJob($job, $data, $queue=null, $delay=0)
    {
        $payload = $this->createPayload($job, $data);

        $job = new Job();
        $job->status = Job::STATUS_OPEN;
        $job->delay = $delay;
        if($queue) $job->queue = $queue;
        $job->payload = $payload;
        $job->save();

        return $job->id;
    }

    /**
     * Make a Process for the Artisan command for the job id.
     *
     * @param int $jobId
     *
     * @return void
     */
    public function startProcess($jobId)
    {
        chdir($this->container['path.base']);
        exec($this->getCommand($jobId));
    }

    /**
     * Get the Artisan command as a string for the job id.
     *
     * @param int $jobId
     *
     * @return string
     */
    protected function getCommand($jobId)
    {
        $cmd = '%s artisan queue:async %d --env=%s';
        $cmd = $this->getBackgroundCommand($cmd);

        $binary = $this->getPhpBinary();
        $environment = $this->container->environment();

        return sprintf($cmd, $binary, $jobId, $environment);
    }

    /**
     * Get the escaped PHP Binary from the configuration
     *
     * @return string
     */
    protected function getPhpBinary()
    {
        $path = escapeshellarg($this->config['binary']);
        $args = $this->config['binary_args'];
        if(is_array($args)){
            $args = implode(' ', $args);
        }
        return trim($path.' '.$args);
    }

    protected function getBackgroundCommand($cmd)
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            return 'start /B '.$cmd.' > NUL';
        } else {
            return $cmd.' > /dev/null 2>&1 &';
        }
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param \DateTime|int $delay
     * @param string        $job
     * @param mixed         $data
     * @param string|null   $queue
     *
     * @return int
     */
    public function later($delay, $job, $data='', $queue=null)
    {
        $delay = $this->getSeconds($delay);
        $id = $this->storeJob($job, $data, $queue, $delay);
        //$this->startProcess($id);
        return 0;
    }
	
	/**
	 * Pop the next job off of the queue.
	 * 
	 * @param string|null  $queue
	 * @return \Illuminate\Queue\Jobs\Job|null
	 */
	public function pop($queue=null) {
		//TODO Il faudrait peut-être tenir compte de la colonne delay (en tt cas si on compte utiliser cette notion pour déterminer quel est le job suivant...)
		if($queue)
			$item=Job::where('queue', '=', $queue)->orderBy('id', 'asc')->first();
		else
			$item=Job::orderBy('id', 'asc')->first();
		if($item)
			return new AsyncJob($this->container, $item);
		return null;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Illuminate\Queue\SyncQueue::pushRaw()
	 */
	public function pushRaw($payload, $queue=null, array $options = array()) {
		$payload=json_decode($payload,true);
		return $this->storeJob($payload['job'], $payload['data'], $queue);
	}
}

<?php

namespace Barryvdh\Queue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Queue jobs.
 *
 * @property int $id
 * @property int $status
 * @property int $retries
 * @property int $delay
 * @property string $queue
 * @property string $payload
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Job extends Model {
	const STATUS_OPEN = 0;
	const STATUS_WAITING = 1;
	const STATUS_STARTED = 2;
	const STATUS_FINISHED = 3;

	protected $table = 'laq_async_queue';
	protected $guarded = array('id', 'created_at', 'updated_at');
	
	public function statustext(){
		switch($this->status) {
			case self::STATUS_OPEN     : return 'created';
			case self::STATUS_WAITING  : return 'waiting';
			case self::STATUS_STARTED  : return 'started';
			case self::STATUS_FINISHED : return 'done';
		}
	}
}

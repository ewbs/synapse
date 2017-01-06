<?php

namespace Barryvdh\Queue\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Failed jobs.
 *
 * @property int $id
 * @property string $connection
 * @property string $queue
 * @property string $payload
 * @property \Carbon\Carbon $failed_at
 */
class FailedJob extends Model {
	protected $table = 'failed_jobs';
	protected $guarded = array('id', 'failed_at');
}

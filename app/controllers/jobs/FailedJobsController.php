<?php

use Barryvdh\Queue\Models\Job;
use Bllim\Datatables\Datatables;
use Barryvdh\Queue\Models\FailedJob;
class FailedJobsController extends BaseController {
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see BaseController::routeGetIndex()
	 */
	protected function routeGetIndex() { return route('jobsGetIndex'); }
	
	/**
	 *
	 * {@inheritDoc}
	 * @see BaseController::getSection()
	 */
	protected function getSection(){
		return 'failedjobs';
	}
	
	/**
	 *
	 * 
	 */
	public function getIndex() {
		return View::make ( 'admin/jobs/failed/list');
	}
	
	/**
	 * Génère la liste des éléments, formatée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public function getData() {
		$items = FailedJob::select ( [
				'id',
				'failed_at',
				'queue',
				'payload',
		]);
		return Datatables::of ( $items )
		->edit_column('id', function (FailedJob $item) {
			return str_pad($item->id, 6, "0", STR_PAD_LEFT);
		})
		->edit_column('payload', function (FailedJob $item) {
			$payload=json_decode($item->payload,true);
			return $payload['job'].', '.$payload['data']['view'];
		})
		->add_column('actions', function (FailedJob $item) {
			return '<a title="'.Lang::get('button.view').'" href="'.route('failedjobsGetView', $item->id).'" class="btn btn-xs btn-default"><span class="fa fa-eye"></a>';
		})
		->make();
	}
	
	/**
	 * Affiche la visualisation d'un failedjob
	 *
	 * @param FailedJob $job
	 * @return \Illuminate\View\View
	 */
	public function getView(FailedJob $job) {
		return View::make('admin/jobs/failed/view', compact('job') );
	}
	
	/**
	 * Demande confirmation de la réinsertion d'un failedjob dans la queue
	 *
	 * @param FailedJob $job
	 * @return \Illuminate\View\View
	 */
	public function getRetry(FailedJob $job) {
		return View::make('admin/jobs/failed/retry', compact('job') );
	}
	
	/**
	 *Réinsertion d'un failedjob dans la queue
	 *
	 * @param FailedJob $job
	 * @return \Illuminate\View\View
	 */
	public function postdRetry(FailedJob $job) {
		$payload=json_decode($job->payload, true);
		$payload['attempts'] = 0;
		if(isset($payload['error'])) unset($payload['error']);
		$payload=json_encode($payload);
		DB::beginTransaction();
		Queue::pushRaw($payload, $job->queue);
		app()['queue.failer']->forget($job->id);
		DB::commit();
		return Redirect::route('jobsGetIndex')->with ( 'success', Lang::get ( 'admin/jobs/messages.retry.success' ) );
	}
}
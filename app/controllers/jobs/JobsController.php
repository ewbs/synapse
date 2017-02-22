<?php

use Barryvdh\Queue\Models\Job;
use Bllim\Datatables\Datatables;
use Barryvdh\Queue\Models\FailedJob;
class JobsController extends BaseController {
	
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
		return 'jobs';
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see BaseController::getIndex()
	 */
	public function getIndex() {
		return View::make ( 'admin/jobs/queue/list');
	}
	
	/**
	 * Génère la liste des éléments, formatée pour les DataTables
	 *
	 * @return Datatables JSON
	 */
	public function getData() {
		$items = Job::select ( [
			'id',
			'created_at',
			'status',
			'retries',
			'queue',
			'payload',
		]);
		return Datatables::of ( $items )
		->edit_column('id', function (Job $item) {
			return str_pad($item->id, 6, "0", STR_PAD_LEFT);
		})
		->edit_column('status', function (Job $item) {
			return '<span class="hidden">'.$item->status.'</span>'.$item->statustext();
		})
		->edit_column('payload', function (Job $item) {
			$payload=json_decode($item->payload,true);
			return $payload['job'].', '.$payload['data']['view'];
		})
		->add_column('actions', function (Job $item) {
			return '<a title="'.Lang::get('button.view').'" href="'.route('jobsGetView', $item->id).'" class="btn btn-xs btn-default"><span class="fa fa-eye"></a>';
		})
		->make();
	}
	
	/**
	 * Affiche la visualisation d'un job
	 *
	 * @param Job $job
	 * @return \Illuminate\View\View
	 */
	public function getView(Job $job) {
		return View::make('admin/jobs/queue/view', compact('job') );
	}
}
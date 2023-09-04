<?php

/**
 * @author kepchuk <support@game-lab.su>
 * @link https://steamcommunity.com/id/kepchuk/
 */

namespace application\controllers;

use application\core\Controller;
use application\models\Main;
use application\models\User;
use application\models\Admin;
use application\lib\System;

class MapsController extends Controller
{
	public function indexAction()
	{
		$mainModel = new Main;
		$system = new System;

		$vars = [
			'statisticServer' => $mainModel->statisticServer(),
			'maps' => $this->model->getMaps(),
			'system' => $system,
			'style' => $system->style(),
		];

		$this->view->render(TITLE_PAGE_MAPS,$vars);
	}

	public function mapinfoAction()
	{
		$mainModel = new Main;
		$userModel = new User;
		$system = new System;

		$vars = [
			'mapname' => $this->route['map'],
			'statisticServer' => $mainModel->statisticServer(),
			'mapinfo' => $this->model->mapinfo($this->route['map']),
			'stylemapbase' => $this->model->stylemapbase($this->route['map']),
			'stylemapbonus' => $this->model->stylemapbonus($this->route['map']),
			'style' => $system->style(),
			'system' => $system,
			'lastRecordsMap' => $this->model->lastRecordsMap($this->route['map']),
		];

		$this->view->render(TITLE_PAGE_MAP.$this->route['map'],$vars);
	}

	public function allracordsAction()
	{
		$mainModel = new Main;
		$userModel = new User;
		$adminModel = new Admin;
		$system = new System;

		$map = $this->route['map'];

		$vars = [
			'mapname' => $this->route['map'],
			'statisticServer' => $mainModel->statisticServer(),
			'mapinfo' => $this->model->mapinfo($this->route['map']),
			'style' => $system->style(),
			'system' => $system,
			'flstyle' => $adminModel->styleList(),
		];

		if (!empty($_POST)) {
			if (!$this->model->mapValidate($_POST,$map)) {
	        	
	        	$this->view->message('error', $this->model->error);
			}
			$url = 'maps/'.$this->route['map'].'/allrecords&style='.$_POST['style'].'&track='.$_POST['track'];
			$this->view->location($url);
		}
		if (!empty($this->route['style'])) {
			$vars['records'] = $this->model->allrecordsfilter($this->route['map'], $this->route['style'], $this->route['track']);
		}else{
			$vars['records'] = $this->model->allrecords($this->route['map']);
		}

		$this->view->render(TITLE_PAGE_MAP_ALLRECORDS.$this->route['map'],$vars);
	}
}
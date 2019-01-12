<?php
namespace Application\controllers;
use Application\models\Curier;
use Application\models\Schedule;
use Application\models\Region;

class MainController 
{
	public function actionIndex()
	{
    	require_once(ROOT . '/application/views/index.php');
	}
}

<?php

class AdminController extends Controller
{
    public $defaultAction = 'prices';
    public $layout = 'main';

    public function actionPrices($siteId = false) {
        if (!Yii::app() -> user -> getState('logged')) {
            $this -> redirect('login');
        }
        $input = $_POST;
        if ($input['site']) {
            $site = Site::model() -> findByPk($input['siteId']);
            /**
             * @type Site $site
             */
            if ($site) {
                $priceList = $site -> buildPriceList();
                $priceList -> script = $input['script'];
                $priceList -> save();
                foreach ($site -> prices as $price) {
                    $newVal = $price -> duplicateLastValue();
                    $newVal -> id_list = $priceList -> id;
                    if (isset($input['price'][$price ->id])) {
                        $newVal -> price = $input['price'][$price -> id];
                        $newVal->last_altered = $priceList->date;
                    }
                    if (!$newVal -> save()) {
                        throw new Exception('Could not save or just link duplicated priceValue');
                    }
                }
                $priceList -> activate();
            }
        } else {
            $site = Site::model()->findByPk($siteId);
            if (!$site) {
                $site = Site::model()->find();
            }
            $site->importPrices();
        }
        if (!$site) {
            $site = Site::model()->find();
        }
        $this -> render('prices',['site' => $site]);
    }
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
<?php

/**
 * This is the model class for table "prmg_sites".
 *
 * The followings are the available columns in table 'prmg_sites':
 * @property integer $id
 * @property string $name
 * @property string $url_price
 * @property string $db_name
 * @property string $pss
 * @property string $table_name
 *
 * @property PriceList[] $priceLists
 * @property Price[] $prices
 */
class Site extends UModel
 {
	public $missingPrices = [];
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'prmg_sites';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('name, db_name', 'required'),
				array('db_name', 'length', 'max'=>256),
				array('url_price', 'safe'),
				// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, url_price, db_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'priceLists' => array(self::HAS_MANY, 'PriceList','id_site', 'order' => 'date DESC', 'limit' => 5),
			'lastList' => array(self::HAS_ONE, 'PriceList','id_site', 'order' => 'date DESC'),
			'prices' => array(self::HAS_MANY, 'Price','id_site'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'name' => 'Name',
				'url_price' => 'Url Price',
				'db_name' => 'Db Name',
			);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search() {
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

			$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('url_price',$this->url_price,true);
		$criteria->compare('db_name',$this->db_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Site the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	public function getPriceLists() {
		return $this -> priceLists;
	}
	public function getConnection() {
		return MYSQLConnection::getConnection([
			'dbName' => $this -> db_name,
			'userName' => $this -> db_name,
			'pss' => $this -> pss
		]);
		/*
		if (!$this -> _connection) {
			$this -> _connection = mysqli_connect('localhost', $this->db_name, $this -> pss, $this->db_name);
		}
		return $this -> _connection;*/
	}
	public function importPrices() {
		$conn = $this -> getConnection();
		try {
			if ($conn) {
				$q = mysqli_query($conn, "SELECT * FROM `{$this->table_name}`");
				$criteria = new CDbCriteria();
				$criteria->addCondition('id_site = '. $this->id.' AND id_price_on_site = :pr_id');
				while ($pr = mysqli_fetch_assoc($q)) {
					$criteria-> params = [
						':pr_id' => $pr['id']
					];
					$price = Price::model()->find($criteria);
					/**
					 * @type Price $price
					 */
					if (!$price) {
						$this->nonExistentPriceFound($pr);
					} else {
						$price -> comparePriceValue($pr);
					}
				}
			}
		} catch(Exception $e) {
			throw $e;
		}
	}
	public function nonExistentPriceFound($pr) {
		$this -> createFromAssoc($pr);
		//$this -> missingPrices[] = $pr;
	}
	public function createFromAssoc($pr) {
		$price = new Price();
		$price -> id_price_on_site = $pr['id'];
		$price -> id_site = $this -> id;
		$price -> name = $pr['text'];
		if (!$price -> save()) {
			$err = $price -> getErrors();
			throw new Exception('could not save new price.');
		}
		$price_value = new PriceValue();
		$price_value -> id_price = $price -> id;
		$price_value -> price = $pr['price'];
		if (!$price_value -> save()) {
			throw new Exception('Could not save new price value.');
		}
	}
	public function giveLastValues() {

	}

	public function buildPriceList() {
		$pricelist = new PriceList();
		$pricelist -> id_site = $this -> id;
		if (!$pricelist -> save()) {
			$err = $pricelist -> getErrors();
			throw new Exception('Could not create new pricelist for site '.$this -> name);
		}
		$pricelist -> refresh();
		return $pricelist;
	}
}

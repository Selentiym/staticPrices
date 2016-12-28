<?php

/**
 * This is the model class for table "prmg_lists".
 *
 * The followings are the available columns in table 'prmg_lists':
 * @property integer $id
 * @property integer $id_site
 * @property string $date
 * @property string $script
 *
 * @property Site $site
 */
class PriceList extends UModel
 {
	public $indexedValues = [];
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'prmg_lists';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id_site', 'required'),
				array('id_site', 'numerical', 'integerOnly'=>true),
				// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_site, date', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'priceValues' => array(self::HAS_MANY, 'PriceValue','id_list'),
			'site' => array(self::BELONGS_TO, 'Site','id_site'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'id_site' => 'Id Site',
				'date' => 'Date',
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
		$criteria->compare('id_site',$this->id_site);
		$criteria->compare('date',$this->date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PriceList the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return int
	 */
	public function getDate() {
		if ($this -> date) {
			return strtotime($this->date);
		} else {
			return time();
		}
	}

	/**
	 * @param Price $price
	 * @return PriceValue
	 */
	public function givePriceValueByPriceId(Price $price) {
		$vals = $this -> getIndexedValues();
		return $vals[$price -> id];
	}

	/**
	 * @return PriceValue[]
	 */
	public function getIndexedValues() {
		if (!$this -> indexedValues) {
			$rez = [];
			foreach($this -> priceValues as $val) {
				$rez[$val -> id_price] = $val;
			}
			$this -> indexedValues = $rez;
		}
		return $this -> indexedValues;
	}
	public function activate() {
		$site = $this -> getSite();
		$conn = $site -> getConnection();

		$params = [];
		foreach ($this -> getRelated('priceValues', true) as $pv) {
			/**
			 * @type PriceValue $pv
			 */
			$id = $pv->priceObj->id_price_on_site;
			$params[] = "('$id', '$pv->price')";
		}
		if (!empty($params)) {
			$sql = 'INSERT INTO `'.$site -> table_name.'` (`id`, `price`) VALUES ';
			$sql .= implode(',',$params);
			$sql .= ' ON DUPLICATE KEY UPDATE `price` = VALUES(`price`)';
			$q = mysqli_query($conn, $sql);
			if ($q) {
				echo "<div>Результаты успешно загружены на сайт ".$this->getSite()->name.'</div>';
			}
		}
	}

	/**
	 * @return Site
	 */
	public function getSite() {
		return $this -> site;
	}
}

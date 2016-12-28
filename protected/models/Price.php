<?php

/**
 * This is the model class for table "prmg_prices".
 *
 * The followings are the available columns in table 'prmg_prices':
 * @property integer $id
 * @property integer $id_site
 * @property string $name
 * @property integer $id_price_on_site
 *
 * @property PriceValue $lastValue
 */
class Price extends UModel
 {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'prmg_prices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id_site, name, id_price_on_site', 'required'),
				array('id_site, id_price_on_site', 'numerical', 'integerOnly'=>true),
				// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_site, name, id_price_on_site', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'priceValues' => array(self::HAS_MANY, 'PriceValue','id_price'),
				'lastValue' => array(self::HAS_ONE, 'PriceValue', 'id_price', 'order' => 'last_altered DESC')
			);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'id_site' => 'Id Site',
				'name' => 'Name',
				'id_price_on_site' => 'Id Price On Site',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('id_price_on_site',$this->id_price_on_site);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Price the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return PriceValue
	 */
	public function getLastValue($refresh = false) {
		return $this -> getRelated('lastValue',$refresh);
	}
	public function comparePriceValue($pr){
		$lastVal = $this -> getLastValue(true);
		if ((($lastVal -> price != $pr['price'])||(!$lastVal))&&($pr['price'])) {
			if (!$this -> giveNewValue($pr['price']) -> save()) {
				throw new Exception('Could not save new price value while refreshing');
			}
		}
	}
	public function giveNewValue($price) {
		$newVal = new PriceValue();
		$newVal -> id_price = $this -> id;
		$newVal -> price = $price;
		return $newVal;
	}
	public function duplicateLastValue() {
		$newVal = $this -> getLastValue(true);
		$attrs = $newVal -> attributes;
		//Если лист не указан, то пересоздавать не нужно - только прицепить (но это уже позже)
		if ($newVal -> id_list > 0) {
			$newVal->setIsNewRecord(true);
			$newVal->id = null;
			$newVal->id_list = null;
		}
		$attrs = $newVal -> attributes;
		return $newVal;
	}
}

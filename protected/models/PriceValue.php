<?php

/**
 * This is the model class for table "prmg_price_values".
 *
 * The followings are the available columns in table 'prmg_price_values':
 * @property integer $id
 * @property integer $id_price
 * @property integer $price
 * @property string $last_altered
 * @property integer $id_list
 *
 * @property PriceList $priceList
 * @property Price $priceObj
 */
class PriceValue extends UModel
 {
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'prmg_price_values';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id_price, price', 'required'),
				array('id_price, price, id_list', 'numerical', 'integerOnly'=>true),
				// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, id_price, price, last_altered, id_list', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'priceObj' => array(self::BELONGS_TO, 'Price','id_price'),
			'priceList' => array(self::BELONGS_TO, 'PriceList','id_list'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'id_price' => 'Id Price',
				'price' => 'Price',
				'last_altered' => 'Last Altered',
				'id_list' => 'Id List',
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
		$criteria->compare('id_price',$this->id_price);
		$criteria->compare('price',$this->price);
		$criteria->compare('last_altered',$this->last_altered,true);
		$criteria->compare('id_list',$this->id_list);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PriceValue the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
}

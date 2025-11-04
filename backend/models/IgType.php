<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%goods}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $image
 * @property string $content
 */
class IgType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ig_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id','name'], 'required'],
            [['name','value'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 500],
            [['goods_id'],'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品ID',
            'image' => '属性图片',
            'name' => '属性名称',
            'value'=>'属性值',
        ];
    }
    /**
     * @param bool $insert
     * @return bool
     * 自动插入
     */
    public function beforeSave($insert)
    {
        /*if($this->isNewRecord)
        {
            $this->append = time();
        }
        else
        {
            $this->updated = time();
        }*/

        return parent::beforeSave($insert);
    }

    public function getTypearr(){
        return $this->hasMany(IgType::className(), ['name' => 'name','goods_id' => 'goods_id']);
    }

    public static function getName($id)
    {
        $idarr = explode(',', $id);
        $name = array();
        foreach ($idarr as $k => $v) {
            $data = self::findOne($v);
            if($data)
            {
                $name[] = $data->value;
            }
        }
        $name = implode(',', $name);
        return $name;

    }

    public static function getType($id)
    {
        $model = self::find()->where(['goods_id'=>$id])->with('typearr')->GroupBy('name')->asArray()->all();
        return $model;
    }

}
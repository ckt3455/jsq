<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%video}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $sign
 * @property string $href
 * @property integer $append
 * @property integer $updated
 * @property integer $image
 */
class Video extends \yii\db\ActiveRecord
{

    public static $type = [
        1=>'技术之窗',
        2=>'解决方案',

    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%video}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'href'], 'required'],
            [['append', 'updated','type'], 'integer'],
            [['title', 'sign'], 'string', 'max' => 50],
            [['href','image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'sign' => '标签',
            'href' => '地址',
            'append' => '创建时间',
            'updated' => '更新时间',
            'type'=>'类型',
            'image'=>'图片'
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * 自动插入
     */
    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {
            $this->append = time();
        }
        else
        {
            $this->updated = time();
        }
        if(strpos($this->href,'<iframe') !== false){
            preg_match('/<iframe[^>]*\s+src="([^"]*)"[^>]*>/is', $this->href, $matched);
            $this->href=$matched[1];
        }

        return parent::beforeSave($insert);
    }

}

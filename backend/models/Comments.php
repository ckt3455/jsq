<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%comments}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property integer $type
 * @property integer $relation_id
 * @property integer $append
 * @property integer $updated
 * @property integer $parent_id
 */
class Comments extends \yii\db\ActiveRecord
{
    public $verifyCode;//验证码
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'relation_id'], 'required'],
            [['user_id', 'type', 'relation_id', 'append', 'updated', 'parent_id','is_show'], 'integer'],
            [['content'], 'string'],
            ['verifyCode', 'captcha','captchaAction'=>'/news/captcha','on'=>'frontend'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户',
            'content' => '内容',
            'type' => '类型',
            'relation_id' => '关联id',
            'append' => '添加时间',
            'updated' => '更新时间',
            'parent_id' => '上级ID',
            'verifyCode'=>'验证码',
            'is_show'=>'是否显示'
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
        return parent::beforeSave($insert);
    }

    /**
     * 关联用户
     */

    public function getUser(){
        return $this->hasOne(ProvinceUser::className(), ['id' => 'user_id']);
    }

}

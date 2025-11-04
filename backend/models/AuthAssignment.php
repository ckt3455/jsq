<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%auth_assignment}}".
 *
 * @property string $item_name
 * @property string $user_id
 * @property integer $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name'     => '角色名称',
            'user_id'       => '用户ID',
            'created_at'    => 'Created At',
        ];
    }

    /**
     * @param $user_id      -用户id
     * @param $item_name    -权限名称
     */
    public function setAuthRole($user_id,$item_name)
    {
        $this::deleteAll(['user_id'=>$user_id]);

        $AuthAssignment = new $this;
        $AuthAssignment->user_id    = $user_id;
        $AuthAssignment->item_name  = $item_name;
        $save = $AuthAssignment->save();

        if($save)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @param $user_id
     * 根据用户ID获取权限名称
     */
    public function getName($user_id)
    {
        $model   = AuthAssignment::find()
            ->where(['user_id'=>$user_id])
            ->one();

        return $model->item_name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
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
            $this->created_at = time();
        }

        return parent::beforeSave($insert);
    }
}

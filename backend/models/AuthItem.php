<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends \yii\db\ActiveRecord
{
    const ROLE = 1;//角色类型
    const AUTH = 2;//权限类别

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'type'], 'required'],
            ['name', 'required','message'=>'内容不能为空'],
            ['name', 'unique','message'=>'名称已存在,请重新输入'],
            [['type','sort', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['key','parent_key'], 'safe'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            ['parent_key', 'default', 'value' => 0],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'          => '路由地址',
            'key'           => '手动增ID',
            'type'          => '类型',
            'description'   => '路由说明',
            'rule_name'     => 'rule名称',
            'data'          => 'Data',
            'sort'          => '排序',
            'parent_key'   => '父级目录',
            'created_at'    => '创建时间',
            'updated_at'    => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('{{%auth_item_child}}', ['parent' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('{{%auth_item_child}}', ['child' => 'name']);
    }


    /**
     * @return bool
     * 删除子数据
     */
    public function afterDelete()
    {
        AuthItem::deleteAll(['parent_key'=>$this->key]);
        return parent::beforeDelete();
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
            //设置key
            $model = self::find()->orderBy('key desc')->select('key')->one();
            $key = $model['key'];
            $this->key = $key ? $key + 1 : 1;

            $this->created_at = time();
        }
        else
        {
            $this->updated_at = time();
        }
        return parent::beforeSave($insert);
    }
}

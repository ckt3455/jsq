<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $sign
 * @property integer $sort
 * @property string $image
 * @property string $content
 * @property integer $append
 * @property integer $updated
 */
class ArticleType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sort', 'append', 'updated','status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['position'], 'safe'],
            [['sort',], 'default', 'value' => 0],
            [['status',], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '分类名',
            'sort' => '排序',
            'status' => '状态',
            'append' => '添加时间',
            'updated' => '更新时间',
            'position'=>'推荐位',
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
            $this->updated = time();
        }
        else
        {
            $this->updated = time();
        }
        if(is_array($this->position)){
            $this->position=implode(',',$this->position);
        }else{
            $this->position='';
        }

        return parent::beforeSave($insert);
    }
    /**
     * 标签判断
     */

    public static function is_position($position,$value){

        $array=explode(',',$position);
        if(!is_array($array)){
            return false;
        }

        if(in_array($value,$array)){
            return true;
        }
        else{
            return false;
        }

    }

    public static function getName($id){
        $model = ArticleType::findOne($id);
        if($model)
        {
            return $model->name;
        }else
        {
            return '未知分类';
        }
    }

    public function getArticle()
    {
        return $this->hasMany(Article::className(), ['type' => 'id'])->where(['status'=>1])->orderBy('sort asc, id desc');
    }

    /**
     *获取分站信息
     */
    public static function getList()
    {
        $area = self::find()->asArray()->all();
        return ArrayHelper::map($area,'id','name');
    }

    /**
     *获取推荐分类及文章
     *$position 推荐位
     */
    public static function getTypeMenu($position)
    {
        $model = self::find()->with(['article'])->where(['status'=>1])->andWhere('FIND_IN_SET('.$position.',position)')->orderBy('sort asc, id desc')->asArray()->all();
        return $model;
    }



}

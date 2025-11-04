<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;

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
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'title'], 'required'],
            [['sort', 'append', 'updated','status','type'], 'integer'],
            [['content'], 'string'],
            [['title', 'link'], 'string', 'max' => 200],
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
            'title' => '标题',
            'content' => '内容',
            'sort' => '排序',
            'status' => '状态',
            'link' => '外链',
            'append' => '添加时间',
            'updated' => '更新时间',
            'type'=>'分类',
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
        }

        if(is_array($this->position)){
            $this->position=implode(',',$this->position);
        }else{
            $this->position='';
        }

        $this->updated = time();

        return parent::beforeSave($insert);
    }

    public function getTypeMessage(){
        return $this->hasOne(ArticleType::className(),['id'=>'type']);
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
    /**
     *获取推荐文章
     *$position 推荐位
     */
    public static function getPosArticle($position)
    {
        $model = self::find()->where(['status'=>1])->andWhere('FIND_IN_SET('.$position.',position)')->orderBy('sort asc, id desc')->asArray()->all();
        return $model;
    }

    /**
     *获取文章链接
     */
    public static function getHref($id){
        $artical=Article::findOne($id);
        if($artical->link){
            $href=$artical->link;
        }
        else{
            $href=Url::to(['index/single','id'=>$id]);
        }
        return $href;

    }



}

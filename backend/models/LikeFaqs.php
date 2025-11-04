<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "{{%faqs}}".
 *
 * @property string $id
 * @property string $qid
 * @property string $uid
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property string $tag
 * @property string $hit
 * @property integer $status
 * @property integer $append
 * @property integer $updated
 */
class LikeFaqs extends \yii\db\ActiveRecord
{
    const status_1 = 0;//待报价
    const status_2 = 1;//受理中
    const status_3 = 2;//已报价
    const status_4 = 3;//已下单

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%like_faqs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qid', 'uid', 'type','append'], 'integer'],
            [['qid', 'uid', 'type'], 'required'],
            [['type'],'default','value'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qid' => '问答ID',
            'uid' => '用户ID',
            'type' => '类型',//0：点赞 1： 狂踩
            'append' => 'Append',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['append'],
                ],
            ],
        ];
    }

    public static function setLike($id,$type=0)
    {
        $faqs = Faqs::findOne($id);
        $uid = Yii::$app->user->identity->id;
        $one=self::find()->where(['qid'=>$id,'uid'=>$uid,'type'=>$type])->One();
        if($one){
            $one->delete();
            if($type == 0){
                $faqs->like = $faqs->like-1;
                $faqs->save();
                return $faqs->like;
            }else{
                $faqs->hate = $faqs->hate-1;
                $faqs->save();
                return $faqs->hate;
            }
        }
        else{
            $model = new LikeFaqs();
            $model->qid = $id;
            $model->type = $type;
            $model->uid = $uid;

            if($model->save()){
                if($type == 0){
                    $faqs->like = $faqs->like+1;
                    $faqs->save();
                    return $faqs->like;
                }else{
                    $faqs->hate = $faqs->hate+1;
                    $faqs->save();
                    return $faqs->hate;
                }
            }else{
                return false;
            }
        }
    }
}

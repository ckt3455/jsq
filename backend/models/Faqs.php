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
 * @property integer $is_show
 * @property integer $is_best
 */
class Faqs extends \yii\db\ActiveRecord
{
    public $faqs;
    const status_1 = 0;//待报价
    const status_2 = 1;//受理中
    const status_3 = 2;//已报价
    const status_4 = 3;//已下单

    public static $status0 = [
        self::status_1 => "等待解答",
        self::status_2 => "已解决",
        self::status_3 => "已删除",
    ];

    public static $status0img = [
        self::status_1 => "查看并处理",
        self::status_2 => "<img src='/Public/frontend/images/correct.png'>",
        self::status_3 => "<img src='/Public/frontend/images/del2.png'>",
    ];

    public static $status1 = [
        self::status_1 => "未被采纳",
        self::status_2 => "被采纳",
    ];

    public static $status1img = [
        self::status_1 => "<img src='/Public/frontend/images/time.png'>",
        self::status_2 => "<img src='/Public/frontend/images/jz.png'>",
    ];

    public static $delect = [
        self::status_1 => "未删除",
        self::status_2 => "已删除",
    ];
    public static $category=[
        0=>'普通类型',
        1=>'技术之窗',
        2=>'解决方案'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%faqs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['qid', 'aid', 'uid', 'type', 'hit', 'status', 'delect', 'append', 'updated', 'hot','hate','like','is_best','is_show'], 'integer'],
            [['uid', 'title', 'content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 200],
            [['tag'], 'string', 'max' => 500],
            [['faqs'],'safe'],
            [['status','hit','type','qid','delect','hot', 'aid','category','like','hate'],'default','value'=>0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'qid' => '回答的提问ID',
            'uid' => '用户ID',
            'aid' => '被求助者ID',
            'title' => '标题',
            'content' => '内容',
            'type' => '类型',
            'tag' => '标签',
            'hit' => '浏览数',
            'hot' => '回答数',
            'delect' => '删除状态',
            'status' => '状态',
            'like'  => '点赞数',
            'hate'  => '狂踩数',
            'append' => 'Append',
            'updated' => 'Updated',
            'category'=>'分类',
            'is_show'=>'是否显示',
            'is_best'=>'精品'
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['append', 'updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord)
        {

        }
        else
        {
            if($this->status==1 and $this->type==1){
                $faq=Faqs::find()->where(['qid'=>$this->qid,'type'=>1,'status'=>1])->one();
                if($faq){
                    $faq->status=0;
                    $faq->save();
                }
            }

        }

        return parent::beforeSave($insert);
    }

    public function getAuser(){
        return $this->hasOne(ProvinceUser::className(),['id'=>'aid']);
    }

    public function getUser(){
        return $this->hasOne(ProvinceUser::className(),['id'=>'uid']);
    }



    public static function delectAnswer($id)
    {
        $one=Faqs::find()->where(['qid'=>$id])->asArray()->One();
        if($one)
        {
            Faqs::findOne($one['id'])->delete();

            Faqs::delectAnswer($one['id']);

        }else{
            return false;
        }
    }

    public static function getMoreAnswer($id,&$arr=array())
    {
        $arr=Faqs::find()->where(['qid'=>$id,'type'=>1,'status'=>0,'is_show'=>1,'delect'=>0])->orderBy('id desc')->asArray()->All();
        if($arr)
        {
            return $arr;

        }else{
            return array();
        }
    }
    /*
    提问采纳的答案
     */
    public static function getAdoptAnswer($id)
    {
        $one=Faqs::find()->where(['qid'=>$id,'type'=>1,'status'=>1])->asArray()->One();
        if($one)
        {
            return $one;

        }else{
            return false;
        }
    }

    /*
    设置提问采纳的答案
     */
    public static function setAdopt($id)
    {
        $one=Faqs::find()->where(['id'=>$id,'type'=>1])->One();

        if($one && $one['qid'] != 0)
        {
            $qone = self::findOne($one['qid']);
            $adopt = self::getAdoptAnswer($one['qid']);
            if(!$adopt && $qone['uid'] == Yii::$app->user->identity->id){
                $one->status = 1;
                if($one->save()){
                    return true;
                }

            }
        }
        return false;
    }

    /**
     * 获取提问的回答数
     */

    public static function aNumber($id){

        $number=Faqs::find()->where(['qid'=>$id,'delect'=>0])->count();

        return $number;

    }

    /**
     * 获取提问用户
     */

    public static function aName($id){

        $one=Faqs::find()->where(['id'=>$id])->One();

        $name = ProvinceUser::getName($one['uid']);
        return $name;
    }

    /**
     * 获取问答点赞数
     */

    public static function getLike($id){

        $one=Faqs::find()->where(['id'=>$id])->One();
        if($one){
            $number = $one['like'];
        }else{
            $number = 0;
        }
        return $number;
    }

    /**
     * 获取问答踩数
     */

    public static function getHate($id){
        $one=Faqs::find()->where(['id'=>$id])->One();
        if($one){
            $number = $one['hate'];
        }else{
            $number = 0;
        }
        return $number;
    }

    /**
     * 获取提问的标签
     */

    public static function aTag($id){

        $one=Faqs::find()->where(['id'=>$id])->One();

        return $one['tag'];

    }

    /**
     * @param $id
     * @return bool
     *  更新回答数
     */
    
    public static function updateHot($id){
        $model = Faqs::findOne($id);
        if($model)
        {
            $model->hot = $model->hot + 1;
            $model->save();
            return true;
        }
    }

    /**
     * @param $id
     * @return bool
     *  更新浏览数
     */
    
    public static function updateHit($id){
        $model = self::findOne($id);
        if($model)
        {
            $model->hit = $model->hit + 1;
            $model->save();
            return true;
        }
    }

    public static function getHotFaqs(){

        /*$model = Faqs::find()->where(['<>','qid',0])
            ->andWhere(['delect'=>0])
            ->select('count(*) as number,qid')
            ->groupBy(['qid'])
            ->orderBy('number desc')
            ->limit(10)
            ->asArray()
            ->all();*/

        $data = Faqs::find()->where(['delect'=>0,'type'=>0,'qid'=>0])
            ->orderBy('hot desc')
            ->limit(10)
            ->asArray()
            ->all();
         
        return $data;
    }

    /**
     * 获取用户的提问或回答数
     */
    public static function getUserCount($type,$user_id){
        if($type==0){
            return count(Faqs::find()->where(['zs_faqs.type'=>$type,'zs_faqs.uid'=>$user_id,'zs_faqs.delect'=>0])->joinWith(['answer'=>function($query){
                $query->where(['q.qid'=>Null]);
            }])->all());
        }
        if($type==1){
            return Faqs::find()->where(['type'=>$type,'uid'=>$user_id,'delect'=>0,'status'=>0])->count();
        }

    }

    /**
     * 技术问答或者解决方案中显示的问答
     */
    public static function getNewsFaqs($category,$keyword){
        $faqs=Faqs::find()->where(['category'=>$category,'delect'=>0,'type'=>0])->andWhere(['or',['like','title',$keyword],['like','content',$keyword]])->orderBy('hit desc')->limit(4)->all();

        return $faqs;


    }

    public function getSolve(){
        return $this->hasOne(Faqs::className(),['qid'=>'id'])->where(['q.status'=>1])->alias('q');
    }
    public function getAnswer(){
        return $this->hasOne(Faqs::className(),['qid'=>'id'])->alias('q');
    }
}

<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%home}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $subtitle
 * @property string $image
 * @property integer $type
 * @property string $href
 * @property integer $sort
 * @property integer $floor
 * @property integer $news_category_id
 * @property integer $user_type
 */
class Home extends \yii\db\ActiveRecord
{
    public static $type = [
        1 => '首页轮播图(760*300)',
        2 => '首页产品广告(97*97)',
        3 => '首页楼层广告(578*136)',
        4 => '首页活动推荐广告(288*250)',
        5 => '首页底部轮播(450*238)',
        6 => '首页底部文章图片(208*114)',
        7 => '品牌广告(238*144)',
        8 => '技术之窗(790*306)',
        12 => '解决方案(790*306)',
        9 => '积分商城(1177*148)',
        10=>'产品列表广告(1390*120)',
        13=>'产品详情页广告(640*65)',
        11=>'会员中心广告(864*109)',
        14=>'虹划算(1920*500)',



    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%home}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort'], 'required'],
            [['sort','type','floor','news_category_id'], 'integer'],
            [['image','href'], 'string', 'max' => 255],
            [['title','subtitle'], 'string', 'max' => 50],
            [['user_type'],'safe']
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
            'subtitle' => '副标题',
            'image' => '图片',
            'type' => '类型',
            'href' => '链接',
            'sort' => '排序',
            'floor' => '楼层',
            'news_category_id' => '文章分类',
            'user_type'=>'会员类型'
        ];
    }

    /**
     * 关联文章
     */

    public function getNews(){
        return $this->hasMany(News::className(), ['category_id' => 'news_category_id'])->orderBy('sort asc')->limit(5);
    }
    public function beforeSave($insert)
    {
        if (is_array($this->user_type)) {
            $this->user_type = implode(',', $this->user_type);
        }


        return parent::beforeSave($insert);
    }

}

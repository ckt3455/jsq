<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%seo}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property integer $category_id
 */
class Seo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seo}}';
    }

    public static $category = [

        '1' => [
            'title' => '商城首页',
            'seo' => [
                '网站名称' => '{web_title}',
            ]
        ],
        '2' => [
            'title' => '品牌中心首页',
            'seo' => [
                '网站名称' => '{web_title}',
            ]
        ],
        '3' => [
            'title' => '虹划算',
            'seo' => [
                '网站名称' => '{web_title}',
            ]
        ],
        '4' => [
            'title' => '技术之窗',
            'seo' => [
                '网站名称' => '{web_title}',
            ]
        ],
        '5' => [
            'title' => '解决方案',
            'seo' => [
                '网站名称' => '{web_title}',
            ]

        ],
        '6' => [
            'title' => '商品列表',
            'seo' => [
                '网站名称' => '{web_title}',
                '搜索到的商品数量' => '{web_goods_number}',
                '商品分类' => '{web_goods_category}',
                '分类关联的品牌' => '{web_category_brands}'

            ]
        ],
        '7' => [
            'title' => '文章列表',
            'seo' => [
                '网站名称' => '{web_title}',
                '所在分类' => '{article_category}'
            ]
        ],
        '8' => [
            'title' => '资料共享',
            'seo' => [
                '网站名称' => '{web_title}',
            ]
        ],
        '9' => [
            'title' => '商品详情',
            'seo' => [
                '网站名称' => '{web_title}',
                '商品名称' => '{goods_title}',
                '商品品牌' => '{goods_brand}',
                '商品关键词' => '{goods_keywords}',
                '品牌关键词' => '{brand_keywords}',
                '所在分类及其上级分类' => '{goods_category_parent}',
            ]
        ],
        '10' => [
            'title' => '品牌展示',
            'seo' => [
                '网站名称' => '{web_title}',
                '品牌名称' => '{brand_title}',
                '品牌英文名' => '{brand_en_title}',
                '品牌别名' => '{brand_second_title}',
                '品牌标签' => '{brand_sign}',
                '栏目设置' => '{brand_set}'
            ]
        ],
        '11' => [
            'title' => '文章详情',
            'seo' => [
                '网站名称' => '{web_title}',
                '所在分类' => '{article_category}',
                '文章标题' => '{article_title}',
                '标签' => '{article_sign}',
            ]
        ]
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'keywords', 'description', 'category_id'], 'required'],
            [['description'], 'string'],
            [['category_id'], 'integer'],
            [['title', 'keywords'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'category_id' => 'Category ID',
        ];
    }

    public static function getSeo($id, $type, $category_id,$content=[])
    {
            if ($id > 0) {
            $detail = SeoDetail::find()->where(['type' => $type, 'relation_id' => $id])->one();
            if ($detail) {
                if($detail->title){
                    return $detail;
                }
            }
        }
            $seo = Seo::find()->where(['category_id' => $category_id])->one();
            switch ($seo->category_id) {
                case 1;
                    $seo->title=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->title);
                    $seo->keywords=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->keywords);
                    $seo->description=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->description);
                    break;
                case 2;
                    $seo->title=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->title);
                    $seo->keywords=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->keywords);
                    $seo->description=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->description);
                    break;
                case 3;
                    $seo->title=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->title);
                    $seo->keywords=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->keywords);
                    $seo->description=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->description);
                    break;
                case 4;
                    $seo->title=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->title);
                    $seo->keywords=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->keywords);
                    $seo->description=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->description);
                    break;
                case 5;
                    $seo->title=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->title);
                    $seo->keywords=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->keywords);
                    $seo->description=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->description);
                    break;
                case 6;
                    $a=["{web_title}","{web_goods_number}","{web_goods_category}","{web_category_brands}"];
                    $b=[Yii::$app->config->info('WEB_SITE_TITLE'),$content['web_goods_number'],$content['web_goods_category'],$content['web_category_brands']];
                    $seo->title=str_replace($a,$b,$seo->title);
                    $seo->keywords=str_replace($a,$b,$seo->keywords);
                    $seo->description=str_replace($a,$b,$seo->description);
                    break;
                case 7;
                    $a=["{web_title}","{article_category}"];
                    $b=[Yii::$app->config->info('WEB_SITE_TITLE'),$content['article_category']];
                    $seo->title=str_replace($a,$b,$seo->title);
                    $seo->keywords=str_replace($a,$b,$seo->keywords);
                    $seo->description=str_replace($a,$b,$seo->description);
                    break;
                case 8;
                    $seo->title=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->title);
                    $seo->keywords=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->keywords);
                    $seo->description=str_replace('{web_title}',Yii::$app->config->info('WEB_SITE_TITLE'),$seo->description);
                    break;
                case 9;
                    $a=["{web_title}","{goods_title}","{goods_brand}","{goods_keywords}","{brand_keywords}","{goods_category_parent}"];
                    $b=[Yii::$app->config->info('WEB_SITE_TITLE'),$content['goods_title'],$content['goods_brand'],$content['goods_keywords'],$content['brand_keywords'],$content['goods_category_parent']];
                    $seo->title=str_replace($a,$b,$seo->title);
                    $seo->keywords=str_replace($a,$b,$seo->keywords);
                    $seo->description=str_replace($a,$b,$seo->description);
                    break;
                case 10;
                    $a=["{web_title}","{brand_title}","{brand_en_title}","{brand_second_title}","{brand_sign}","{brand_set}"];
                    $b=[Yii::$app->config->info('WEB_SITE_TITLE'),$content['brand_title'],$content['brand_en_title'],$content['brand_second_title'],$content['brand_sign'],$content['brand_set']];
                    $seo->title=str_replace($a,$b,$seo->title);
                    $seo->keywords=str_replace($a,$b,$seo->keywords);
                    $seo->description=str_replace($a,$b,$seo->description);
                    break;
                case 11;
                    $a=["{web_title}","{article_category}","{article_title}","{article_sign}"];
                    $b=[Yii::$app->config->info('WEB_SITE_TITLE'),$content['article_category'],$content['article_title'],$content['article_sign']];
                    $seo->title=str_replace($a,$b,$seo->title);
                    $seo->keywords=str_replace($a,$b,$seo->keywords);
                    $seo->description=str_replace($a,$b,$seo->description);
                    break;

            }
            return $seo;



    }

}

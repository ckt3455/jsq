<?php

namespace backend\controllers;

use Yii;
use backend\search\GoodsCategorySearch;
use backend\models\GoodsCategory;
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

use yii\helpers\Url;

/**
 * GoodsCategoryController implements the CRUD actions for GoodsCategory model.
 */
class GoodsCategoryController extends MController
{
    public function actions()
    {
        return [
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => GoodsCategory::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => GoodsCategory::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => GoodsCategory::className(),
            ],
        ];
    }


    public function actionIndex()
    {
        $models = GoodsCategory::find()->where(['parent_id' => '0','language'=>Yii::$app->language])->orderBy('sort Asc')->all();
        return $this->render('index', [
            'models' => $models,
        ]);

    }
    protected function findModel($id)
    {

        if ($id) {
            $model = GoodsCategory::findOne($id);
            if ($model) {
                return $model;
            }
        }
        $model = new GoodsCategory();
        $model->loadDefaultValues();
        return $model;
    }


    public function actionEdit()
    {

        $request = Yii::$app->request;

        $id = $request->get('id');
        $level = $request->get('level');
        $pid = $request->get('parent_id');

        $model = $this->findModel($id);

        //等级
        !empty($level) && $model->level = $level;
        //上级id
        !empty($pid) && $model->parent_id = $pid;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('/layer/close');
        } else {
            return $this->render('edit', [
                'model' => $model,
            ]);
        }
    }

    public function actionGetChildren()
    {
        $id = Yii::$app->request->get('id');
        $children = GoodsCategory::find()->where(['parent_id' => $id])->orderBy('sort asc,id desc')->all();
        $html = '';
        foreach ($children as $k => $v) {
            $parent = GoodsCategory::findOne($v->parent_id);
            if ($v->level == 2) {
                $juli = '└── ';
                $content = $parent->id . ' 0';
            }
            if ($v->level == 3) {
                $juli = ' &nbsp;&nbsp;&nbsp; └────  ';
                $parent_parent = GoodsCategory::findOne($parent->parent_id);
                $content = $parent->id . ' ' . $parent_parent->id . ' 0';
            }
            if ($v->children) {
                $sign = '   <div onclick="get_children(&quot;' . $v->id . '&quot;,$(this))" class="fa cf fa-plus-square" style="cursor:pointer;"></div>';
            } else {
                $sign = '';
            }
            $add = '';
            if ($v->level <= 1) {
                $add = '   <a type="button" class="btn btn-info btn-sm" href="javascript:void(0);" onclick="viewLayer(&quot;' . Url::to(['edit', 'parent_id' => $v->id, 'level' => $v->level + 1]) . '&quot;,$(this))">
                                       添加下级
                                    </a>';
            }
            $html .= '<tr id="' . $v->id . '" class="' . $content . '" style="display: table-row;">
        <td>
                         
                    </td>
        <td>
            　　
                ' . $sign . $juli . $v->title . '&nbsp;
          
                    </td>
                       <td class="col-md-1"><input type="text" class="form-control" value="' . $v->sort . '" onblur="sort(this)"></td>
                                <td>
                                    ' . $add . '
                                    <a href="javascript:void(0);"  type=\"button\" class="btn btn-info btn-sm"  onclick="viewLayer(&quot;' . Url::to(['edit', 'id' => $v->id]) . '&quot;,$(this))" data-pjax=\'0\' > 编辑</a>
                                     <a href="javascript:void(0);"  type=\"button\" class="btn btn-info btn-sm"  onclick="viewLayer(&quot;' . Url::to(['category-icon/index', 'category_id' => $v->id]) . '&quot;,$(this))" data-pjax=\'0\' > 图标设置</a>
                                    <a  data-method="post" data-pjax="0" data-confirm="确定要删除吗？"  href="' . Url::to(['delete', 'id' => $v->id]) . '"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
                                </td>
    </tr>';
        }
        return json_encode($html);
    }


    //获取树状结构分类,最多三级
    public function actionCategoryTree()
    {
        if (Yii::$app->request->get('id')) {
            $category = GoodsCategory::find()->where(['parent_id' => Yii::$app->request->get('id'),'language'=>Yii::$app->language])->all();

        } else {
            $category = GoodsCategory::find()->where(['parent_id' => 0,'language'=>Yii::$app->language])->all();
        }

        $children = [];
        foreach ($category as $k => $v) {
            $children[$k]['id'] = $v->id;
            $children[$k]['text'] = $v->title;
            if ($v->children) {
                foreach ($v->children as $k2 => $v2) {
                    $children[$k]['children'][$k2]['id'] = $v2->id;
                    $children[$k]['children'][$k2]['text'] = $v2->title;
                    if ($v2->children) {
                        foreach ($v2->children as $k3 => $v3) {
                            $children[$k]['children'][$k2]['children'][$k3]['id'] = $v3->id;
                            $children[$k]['children'][$k2]['children'][$k3]['text'] = $v3->title;
                        }
                    }

                };
            }
        }

        return  json_encode($children);


    }



}

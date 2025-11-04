<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>
use backend\widgets\Bar;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?php " . ($generator->indexWidgetType === 'grid' ? "" : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

<?= $generator->enablePjax ? '<?php Pjax::begin(); ?>' : '' ?>
                <?= "<?= Bar::widget()?>" ?>
<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        'export' => false,
    'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        <?= !empty($generator->searchModelClass) ? "'columns' => [\n" : "'columns' => [\n"; ?>
    [
    'headerOptions' => ['width' => '20'],
    'class' => 'yii\grid\CheckboxColumn',
    'name' => 'id',
    ],
            ['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {

            echo "            '" . $name . "',\n";

    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if(strpos($column->name,'is_')!==false){
            ?>
             [
                'attribute'=>'<?php echo $column->name ?>',
                'format'=>'raw',
                'value'=>function($data)
                    {
                        $class_name=json_encode($data->className(),JSON_UNESCAPED_SLASHES);
                        return SwitchInput::widget([
                            'name' => '<?php echo $column->name ?>'.$data->id,
                            'value'=>$data-><?php echo $column->name ?>,
                             'pluginOptions'=>[
                                    'size' => 'small',
                                ],
                                'options'=>[
                                     'onchange'=>'ajax_status('.$class_name.','.$data->id.',"<?php echo $column->name ?>")',
                                    ]
                                  ]);
                    }
            ],
                <?php
        }elseif(strpos($column->name,'image_')!==false){
            ?>

            [
                'attribute' => '<?php echo $column->name ?>',
                'class' => 'kartik\grid\EditableColumn',
                'editableOptions' => [
                'inputType' => \kartik\editable\Editable::INPUT_WIDGET,
                'widgetClass' => 'backend\widgets\webuploader\Image',
                'options' => [
                'boxId' => '<?php echo $column->name ?>',
                'options' => [
                    'multiple' => false,
                 ]
                ],
            ],
            'format' => 'html',
            'value' => function ($data) {
                if ($data-><?php echo $column->name ?>) {
                    $return = "<img style='width:50px;' src='" . $data-><?php echo $column->name ?> . "'>";
                    return $return;
                 }
                }
            ],

            <?php

        }else{
            if($column->name !='id'){
                echo "\n[\n'attribute'=>'" . $column->name . "',\n'class'=>'kartik\grid\EditableColumn'\n],";
            }

        }

    }
}
?>

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}',
                'buttons'=>[
                      'update'=>function($url,$model,$key){
                        return "<a href='javascript:void(0);'  type=\"button\" class=\"btn btn-primary btn-sm\"  onclick=\"viewLayer('$url',$(this))\" data-pjax='0' > 编辑</a>";

                            },
                        'delete'=>function($url,$model,$key){
                         return "<a   type=\"button\" class=\"btn btn-warning btn-sm\"  href=\"$url\" data-method='post' data-pjax='0' data-confirm='确定要删除吗？'> 删除</a>";

    },
    ]


            ],
        ],
    'pager' =>[
    'class' => \common\components\GoPager::className(),
    'firstPageLabel' => '首页',
    'prevPageLabel' => '《',
    'nextPageLabel' => '》',
    'lastPageLabel' => '尾页',
    'goPageLabel' => true,
    'totalPageLable' => '共x页',
    'goButtonLable' => 'GO',
    'maxButtonCount' => 5
    ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>
            </div>
        </div>
    </div>
</div>


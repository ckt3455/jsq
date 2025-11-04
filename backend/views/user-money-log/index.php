<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\widgets\Bar;



$this->title = '金额变动日志';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
    'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
    [
    'headerOptions' => ['width' => '20'],
    'class' => 'yii\grid\CheckboxColumn',
    'name' => 'id',
    ],
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                    'attribute'=>'user_id',
                    'value'=>function($data){
                        if($data->user){
                            return $data->user->name;
                        }
                    }
            ],
            [
                'attribute'=>'admin_id',
                'value'=>function($data){
                    if($data->admin){
                        return $data->admin->username;
                    }
                }
            ],
            [
                'attribute'=>'status',
                'value'=>function($data){
                   if($data->status==1){
                       return '增加';
                   }
                   if($data->status==2){
                       return '减少';
                   }
                }
            ],

            'money',
            'created_at:datetime',
            // 'admin_id',
        ],
    ]); ?>
            </div>
        </div>
    </div>
</div>


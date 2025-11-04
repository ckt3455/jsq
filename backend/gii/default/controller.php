<?php


use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
<?php if (!empty($generator->searchModelClass)): ?>
use <?=$generator->searchModelClass . ";\n"?>
<?php endif; ?>
use <?= $generator->modelClass . ";\n" ?>
use backend\actions\CreateAction;
use backend\actions\UpdateAction;
use backend\actions\IndexAction;
use backend\actions\DeleteAction;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends MController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => <?= $modelClass ?>::className(),
                'data' => function(){
                    <?php if (!empty($generator->searchModelClass)): ?>

                        $searchModel = new <?=StringHelper::basename($generator->searchModelClass)?>();
                        $dataProvider = $searchModel->search(Yii::$app->getRequest()->getQueryParams());
                        return [
                            'dataProvider' => $dataProvider,
                            'searchModel' => $searchModel,
                        ];
                    <?php else: ?>

                        $dataProvider = new ActiveDataProvider([
                            'query' => <?= $modelClass ?>::find(),
                        ]);

                        return [
                            'dataProvider' => $dataProvider,
                        ];
                    <?php endif; ?>

                }
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => <?= $modelClass ?>::className(),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => <?= $modelClass ?>::className(),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => <?= $modelClass ?>::className(),
            ],
        ];
    }
}

<?php
/**
 * Author: lf
 * Blog: https://blog.feehi.com
 * Email: job@feehi.com
 * Created at: 2016-06-15 09:25
 */

namespace backend\widgets;

use yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

class Bar extends Widget
{
    public $buttons = [];

    public $options = [
        'class' => 'mail-tools tooltip-demo m-t-md',
    ];
    public $template = "{create} {delete}";


    /**
     * @inheritdoc
     */
    public function run()
    {
        $buttons = '';
        $this->initDefaultButtons();
        $buttons .= $this->renderDataCellContent();
        return "<div class='{$this->options['class']}'>{$buttons}</div>";
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent()
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                return $this->buttons[$name] instanceof \Closure ? call_user_func($this->buttons[$name]) : $this->buttons[$name];
            } else {
                return '';
            }


        }, $this->template);
    }

    /**
     * 生成默认按钮
     *
     */
    protected function initDefaultButtons()
    {

        if (! isset($this->buttons['create'])) {
            $this->buttons['create'] = function () {
                return Html::a('<i class="fa fa-plus"></i> ' . '添加', "javascript:void(0);", [
                    'title' => '添加',
                    'data-pjax' => '0',
                    'class' => 'btn btn-white btn-sm',
                    'onclick'=>"viewLayer('".Url::to(['create'])."',$(this))"
                ]);
            };
        }

        if (! isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function () {
                return Html::a('<i class="fa fa-trash-o"></i> ' . '批量删除', Url::to(['delete']), [
                    'title' => yii::t('app', 'Delete'),
                    'data-pjax' => '0',
                    'data-confirm' => yii::t('app', 'Really to delete?'),
                    'class' => 'btn btn-white btn-sm multi-operate',
                ]);
            };
        }

        if (! isset($this->buttons['export'])) {
            $this->buttons['export'] = function () {
                return Html::a('<i class="fa fa-export"></i> ' . '导出', Url::to(['export']), [
                    'title' => '导出',
                    'data-pjax' => '0',
                    'class' => 'btn btn-white btn-sm refresh',
                ]);
            };
        }
    }
}
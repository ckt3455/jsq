<?php

namespace common\components;

use yii\helpers\Html;
use yii\widgets\LinkPager;

class GoPager extends LinkPager
{
    /**
     * @var boole|string. weather show totalPage
     *You can do not set the property by using defult,so the totalPage button not show in the html.
     *if you set it as string,eg: totalPageLable => '共x页'.note that the 'x' will be replaced by $pageCount.so the 'x' must be seted.
     */
    public $totalPageLable = false;

    /**
     * @var boole if is seted true,the goPageLabel can be show in the html.
     *
     */
    public $goPageLabel = false;

    /**
     * @var array.options about the goPageLabel(input)
     *goPageLabelOptions => [
     *        'class' =>
     *        'data-num' =>
     *        'style' =>
     *    ]
     *
     */
    public $goPageLabelOptions = [];

    /**
     * @var boole | string. weather show in go page button
     *
     */
    public $goButtonLable = false;

    /**
     * @var array.options about the goButton
     *
     */
    public $goButtonLableOptions = [];

    /**
     *
     **/
    public function init()
    {
        parent::init();

    }

    public function run()
    {
        if ($this->registerLinkTags) {
            $this->registerLinkTags();
        }
        echo $this->renderPageButtons();
    }

    protected function renderPageButtons()
    {

        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();
        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }

        // total page
        if ($this->totalPageLable) {
            $buttons[] = Html::tag('li', Html::a(str_replace('x', $pageCount, $this->totalPageLable), 'javascript:return false;', []), []);
        }

        //gopage
        if ($this->goPageLabel) {
            $input = Html::input('number', $this->pagination->pageParam, $currentPage + 1, array_merge([
                'min' => 1,
                'max' => $pageCount,
                'style' => 'height:34px;width:80px;display:inline-block;margin:0 3px 0 3px',
                'class' => 'form-control',
                'id' => 'go_pages'
            ], $this->goPageLabelOptions));

            $buttons[] = Html::tag('li', $input, []);
        }

        // gobuttonlink
        if ($this->goPageLabel) {
            $url = \Yii::$app->request->getHostInfo() . \Yii::$app->request->url;
            $script = <<<JS
             var url = window.location.href;
            var arg_val = $('#go_pages').val();
            var arg='page';
            var pattern=arg+'=([^&]*)'; 
    var replaceText=arg+'='+arg_val; 
    if(url.match(pattern)){ 
        var tmp='/('+ arg+'=)([^&]*)/gi'; 
        url=url.replace(eval(tmp),replaceText);
    }else{ 
        if(url.match('[\?]')){ 
            url=url+'&'+replaceText;
        }else{ 
            url=url+'?'+replaceText;
        } 
    }
    window.location.href=""+url+"";
JS;
            $buttons[] = Html::buttonInput($this->goButtonLable ? $this->goButtonLable : '跳转', array_merge([
                'style' => 'height:34px;display:inline-block;',
                'class' => 'btn btn-primary',
                'onclick' => "$script"
            ], $this->goButtonLableOptions));
        }
        $ul = Html::tag('ul', implode("\n", $buttons), $this->options);
        return Html::tag('form', $ul, []);
    }
}


?>
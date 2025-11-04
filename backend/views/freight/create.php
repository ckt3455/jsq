<?php
/* @var $model backend\models\Freight */

$this->title = '添加';
$this->params['breadcrumbs'][] = ['label' => '运费模板', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$province = \backend\models\Provinces::find()->where(['parentid' => 0])->all();
?>
<style type="text/css" media="screen">
    .province .cityall{margin-top: 10px!important;}
    .province ul .city{margin-top: 8px!important;}
</style>

<div class="wrapper wrapper-content">
    <form method="post">
        <div class="wb-container">
            <div class="page-content">
                <div class="form-horizontal form-validate">
                    <div class="form-group">
                        <label class="col-lg control-label">排序</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" name="sort" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg control-label must">名称</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" id="dispatchname" name="title" class="form-control" value=""
                                   data-rule-required="true" aria-required="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg control-label ">是否默认</label>
                        <div class="col-sm-9 col-xs-12">
                            <label class="radio-inline"><input type="radio" name="is_default" id="isdefault1" value="1" >
                                是</label>
                            <label class="radio-inline"><input type="radio" name="is_default" id="isdefault0" value="0"
                                                               checked=""> 否</label>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg control-label ">是否使用</label>
                        <div class="col-sm-9 col-xs-12">
                            <label class="radio-inline"><input type="radio" name="status"  value="1"  checked="">
                                是</label>
                            <label class="radio-inline"><input type="radio" name="status"  value="0"
                                                              > 否</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg control-label ">详细介绍</label>
                        <div class="col-sm-9 col-xs-12">
                            <?php echo \kucha\ueditor\UEditor::widget([
                                    'name'=>'content',
                                    'id'=>'content',

                            ])?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg control-label ">计费方式</label>
                        <div class="col-sm-9 col-xs-12">
                            <label class="radio-inline"><input type="radio" name="type" value="1" checked="">
                                按重量计费</label>
                            <label class="radio-inline"><input type="radio" name="type" value="2"> 按件计费</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg control-label " style="padding-top: 11px">配送区域</label>
                        <div class="col-sm-9 col-xs-12">
                            <table style="width:100%;">
                                <thead>
                                <tr>
                                    <th style="height:40px;width:400px;">运送到</th>
                                    <th class="show_h" style="width:110px;">首重(千克)/首件</th>
                                    <th class="show_h" style="width:110px;">首费(元)</th>
                                    <th class="show_h" style="width:110px;">续重(千克)/续件</th>
                                    <th class="show_h" style="width:110px;">续费(元)</th>
                                </tr>
                                </thead>
                                <tbody id="tbody-areas">
                                <tr>
                                    <td style="padding:10px;">全国 [默认运费]</td>
                                    <td class="show_h text-center">
                                        <input type="text" value="" class="form-control" name="firest"
                                               style="width:80px;">
                                    </td>
                                    <td class="show_h text-center">
                                        <input type="text" value="" class="form-control" name="firest_money"
                                               style="width:80px;">
                                    </td>
                                    <td class="show_h text-center">
                                        <input type="text" value="1000" class="form-control" name="next"
                                               style="width:80px;">
                                    </td>
                                    <td class="show_h text-center">
                                        <input type="text" value="" class="form-control" name="next_money"
                                               style="width:80px;">
                                    </td>
                                    <td class="show_n text-center" style="display: none;">
                                        <input type="text" value="1" class="form-control" name="default_firstnum"
                                               style="width:80px;">
                                    </td>
                                    <td class="show_n text-center" style="display: none;">
                                        <input type="text" value="" class="form-control" name="default_firstnumprice"
                                               style="width:80px;">
                                    </td>
                                    <td class="show_n text-center" style="display: none;">
                                        <input type="text" value="1" class="form-control" name="default_secondnum"
                                               style="width:80px;">
                                    </td>
                                    <td class="show_n text-center" style="display: none;">
                                        <input type="text" value="" class="form-control" name="default_secondnumprice"
                                               style="width:80px;">
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <a class="btn btn-default" href="javascript:;" onclick="addArea(this)"><span
                                    class="fa fa-plus"></span> 新增配送区域</a>
                            <span class="help-block show_h">根据重量来计算运费，当物品不足《首重重量》时，按照《首重费用》计算，超过部分按照《续重重量》和《续重费用》乘积来计算</span>
                            <span class="help-block show_n" style="display:none">根据件数来计算运费，当物品不足《首件数量》时，按照《首件费用》计算，超过部分按照《续件重量》和《续件费用》乘积来计算</span>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg control-label "></label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="submit" value="提交" class="btn btn-primary">
                            <input type="button" name="back" onclick="history.back()" style="margin-left:10px;"
                                   value="返回列表" class="btn btn-default">
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>

    <div id="modal-areas" class="modal fade in" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h3>选择区域</h3></div>
                <div class="modal-body clearfix">
                    <?php foreach ($province as $k => $v) { ?>
                        <div class="province">
                            <label class="checkbox-inline" style="margin-left:20px;padding: 0 0 5px 24px;">
                                <input type="checkbox" id="<?php echo $v->id; ?>" class="cityall"
                                       style="margin-top: -10px"> <?php echo $v->areaname ?>
                                <span class="citycount" style="color:#ff6600"></span>
                            </label>
                            <ul style="padding-bottom: 15px;">
                                <?php foreach ($v->children as $k2 => $v2) { ?>
                                    <li>
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="city" id="<?php echo $v2->areaname ?>"
                                            style="margin-top:-3px;" city="<?php echo $v2->id ?>"> <?php echo $v2->areaname ?>
                                        </label>
                                    </li>
                                <?php } ?>

                            </ul>
                        </div>
                    <?php } ?>

                </div>

                <div class="modal-footer">
                    <a href="javascript:;" id="btnSubmitArea" class="btn btn-primary" data-dismiss="modal"
                       aria-hidden="true">确定</a>
                    <a href="javascript:;" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        .province {
            float: left;
            position: relative;
            width: 170px;
            height: 35px;
            line-height: 35px;
            border: 1px solid #fff;
            padding-top: -10px
        }

        .province:hover {
            border: 1px solid #a6d2fe;
            border-bottom: 1px solid #a6d2fe;
            background: #edf6ff;
        }

        .province .cityall {
            margin-top: 10px;
        }

        .province ul {
            list-style: outside none none;
            position: absolute;
            padding: 0;
            background: #edf6ff;
            border: 1px solid #a6d2fe;
            display: none;
            width: auto;
            width: 250px;
            z-index: 999999;
            left: -1px;
            top: 32px;
        }

        .province ul li {
            float: left;
            min-width: 60px;
            margin-left: 20px;
            height: 30px;
            line-height: 30px;
        }

        .checkbox-inline {
            margin: 0;
        }

        .control-label {
            float: left;
        }
    </style>

    <script type="text/javascript">

        $(function () {

            $('.province').mouseenter(function () {
                $(this).find('ul').show();
            }).mouseleave(function () {
                $(this).find('ul').hide();
            });

            $('.cityall').click(function () {
                var checked = $(this).get(0).checked;
                var citys = $(this).parent().parent().find('.city');
                citys.each(function () {
                    $(this).get(0).checked = checked;
                });
                var count = 0;
                if (checked) {
                    count = $(this).parent().parent().find('.city:checked').length;
                }
                if (count > 0) {
                    $(this).next().html("(" + count + ")");
                }
                else {
                    $(this).next().html("");
                }
            });
            $('.city').click(function () {
                var checked = $(this).get(0).checked;
                var cityall = $(this).parent().parent().parent().parent().find('.cityall');

                if (checked) {
                    cityall.get(0).checked = true;
                }
                var count = cityall.parent().parent().find('.city:checked').length;
                if (count > 0) {
                    cityall.next().html("(" + count + ")");
                }
                else {
                    cityall.next().html("");
                }
            });

        });

        function clearSelects() {
            $('.city').attr('checked', false).removeAttr('disabled');
            $('.cityall').attr('checked', false).removeAttr('disabled');
            $('.citycount').html('');
        }

        function editArea(btn) {
            current = $(btn).attr('random');
            clearSelects();
            var old_citys = $(btn).prev().val().split(';');
            console.log(old_citys);

            $("#modal-areas").modal();
            var citystrs = '';
            $('#btnSubmitArea').unbind('click').click(function () {
                var citystrs1 = '', city_ids1 = '';

                $('.city:checked').each(function () {
                    citystrs1 += $(this).attr('city') + ";";
                    city_ids1 += $(this).attr("id") + ',';
                });
                city_ids1 = city_ids1.substring(0, city_ids1.length - 1);
                $(".r" + current + "").find(".city_ids").val(city_ids1);
                $(".r" + current + "").find(".citys").val(citystrs1);
                $(".r" + current + "").find(".rand_1").text(city_ids1);

            })
            var currents = getCurrents(current);
            currents = currents.split(';');
            var citys = "";
            $('.city').each(function () {
                var parentdisabled = false;
                //console.log(currents);
                for (var i in currents) {
                    if (currents[i] != '' && currents[i] == $(this).attr('city')) {
                        $(this).attr('disabled',true);
                        $(this).parent().parent().parent().parent().find('.cityall').attr('disabled',true);
                    }
                }

            });

            $('.city').each(function () {
                var parentcheck = false;

                for (var i in old_citys) {
                    console.log(old_citys[i]);
                    if (old_citys[i].replace(/\s+/g,"") == $(this).attr('city')) {
                        parentcheck = true;
                        $(this).get(0).checked = true;
                        $(this).get(0).disabled = false;
                        console.log($(this).get(0))
                        
                        break;
                    }
                }
                if (parentcheck) {
                    console.log('选中');
                    $(this).parent().parent().parent().parent().find('.cityall').get(0).checked = true;
                    $(this).parent().parent().parent().parent().find('.cityall').get(0).disabled=false;
                }

            });


        }

        function selectAreas() {
            clearSelects();
            var old_citys = $('#areas').html().split(';');


            $('.city').each(function () {
                var parentcheck = false;
                for (var i in old_citys) {
                    if (old_citys[i] == $(this).attr('city')) {
                        parentcheck = true;
                        $(this).get(0).checked = true;
                        break;
                    }
                }
                if (parentcheck) {
                    $(this).parent().parent().parent().parent().find('.cityall').get(0).checked = true;
                }
            });

            $("#modal-areas").modal();
            var citystrs = '';
            $('#btnSubmitArea').unbind('click').click(function () {
                $('.city:checked').each(function () {
                    citystrs += $(this).attr('city') + ";";
                });
                $('#areas').html(citystrs);
                $("#selectedareas").val(citystrs);
            })

        }




        $(function () {
            $(':radio[name=isdispatcharea]').click(function () {
                var val = $(this).val();
                var name = '不';
                if (val == 1) {
                    name = '只';
                }
                $("#dispatcharea_name").html(name);
            })

            $("select[name=express]").change(function () {
                var obj = $(this);
                var sel = obj.find("option:selected");
                $(":input[name=expressname]").val(sel.data("name"));
            });

            $('.province').mouseenter(function () {
                $(this).find('ul').show();
            }).mouseleave(function () {
                $(this).find('ul').hide();
            });

            $('.cityall').click(function () {
                var checked = $(this).get(0).checked;
                var citys = $(this).parent().parent().find('.city');
                citys.each(function () {
                    $(this).get(0).checked = checked;
                });
                var count = 0;
                if (checked) {
                    count = $(this).parent().parent().find('.city:checked').length;
                }
                if (count > 0) {
                    $(this).next().html("(" + count + ")");
                }
                else {
                    $(this).next().html("");
                }
            });

            $('.city').click(function () {
                var checked = $(this).get(0).checked;
                var cityall = $(this).parent().parent().parent().parent().find('.cityall');
                if (checked) {
                    cityall.get(0).checked = true;
                }
                var count = cityall.parent().parent().find('.city:checked').length;
                if (count > 0) {
                    cityall.next().html("(" + count + ")");
                }
                else {
                    cityall.next().html("");
                }
            });
        });

        function getCurrents(withOutRandom) {
            var citys = "";
            $('.citys').each(function () {
                var crandom = $(this).prev().val();
                if (withOutRandom && crandom == withOutRandom) {
                    return true;
                }
                citys += $(this).val();
            });
            return citys;
        }

        function getCurrentsCode(withOutRandom) {
            var citys = "";
            $('.citys_code').each(function () {
                var crandom = $(this).prev().prev().prev().val();
                if (withOutRandom && crandom == withOutRandom) {
                    return true;
                }
                citys += $(this).val();
            });
            return citys;
        }

        var current = '';

        var rand = 1;

        function addArea(btn) {
            $("#modal-areas").modal();
            clearSelects();
            var citystrs = "", city_ids = '';
            var currents = getCurrents();
            currents = currents.split(';');
            $('.city').each(function () {
                var parentdisabled = false;
                for (var i in currents) {
                    if (currents[i] != '' && currents[i] == $(this).attr('city')) {
                        $(this).attr('disabled', true);
                        $(this).parent().parent().parent().parent().find('.cityall').attr('disabled', true);
                    }
                }
            });
            $('#btnSubmitArea').unbind('click').click(function () {
                current = 'rand_' + (rand++);
                $('.city:checked').each(function () {
                    citystrs += $(this).attr('city') + ";";
                    city_ids += $(this).attr("id") + ',';
                });

                city_ids = city_ids.substring(0, city_ids.length - 1);

                var con = '<tr class="r' + current + '">\
                        <td style="word-break:break-all;width:80px;padding:10px;line-height:22px;overflow:hidden; white-space: normal;">\
                            <span class="' + current + '">' + city_ids + '</span>\
                            <input type="hidden" class="city_ids" value="' + city_ids + '" />\
                            <input type="hidden" class="citys" name="citys[]" value="' + citystrs + '">\
                            <a href="javascript:;" onclick="editArea(this)" random="' + current + '">编辑</a>\
                            <input type="hidden" class="citys_code" name="citys_code[]" value="">\
                        </td>\
                        <td class="show_h text-center" valign="top" style="padding-top:10px;"><input type="text" value="" class="form-control" name="firstweight[]" style="width:80px;"></td>\
                        <td class="show_h text-center" valign="top" style="padding-top:10px;"><input type="text" value="" class="form-control" name="firstprice[]" style="width:80px;"></td>\
                        <td class="show_h text-center" valign="top" style="padding-top:10px;"><input type="text" value="1000" class="form-control" name="secondweight[]" style="width:80px;"></td>\
                        <td class="show_h text-center" valign="top" style="padding-top:10px;"><input type="text" value="" class="form-control" name="secondprice[]" style="width:80px;"></td>\
                        <td style="text-align: center;padding-top:10px;" valign="top"><a href="javascript:;" class="btn btn-default btn-sm" onclick="$(this).parent().parent().remove()">删除</a></td>\
                    </tr>';
                $("#tbody-areas").append(con);

            })
        }


    </script>
</div>



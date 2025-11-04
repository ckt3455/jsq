// rem初始化
function recalc() {
    var clientWidth = $(window).width();
    if (clientWidth > 750) {
        clientWidth = 750;
    }
    if (clientWidth < 320) {
        clientWidth = 320;
    }
    $('html').css({
        fontSize: 100 * (clientWidth / 750)
    });
}
$(function() {
    $(window).resize(function() {
        recalc();
    });
    recalc();
    // 选项卡 鼠标点击
    $(".TAB_CLICK li").click(function() {
        var tab = $(this).parent(".TAB_CLICK");
        var con = tab.attr("id");
        var on = tab.find("li").index(this);
        $(this).addClass('on').siblings(tab.find("li")).removeClass('on');
        $(con).eq(on).show().siblings(con).hide();
    });

    $('.TAB_CLICK').each(function(index, el) {
        $(this).find("li").filter(':first').trigger('click');
    });
    /**单选多选 */
    $('[role=radio]').each(function() {
        var input = $(this).find('input[type="radio"]'),
            label = $(this).find('label');
        input.each(function() {
            if ($(this).attr('checked')) {
                $(this).parents('label').addClass('checked');
                $(this).prop("checked", true)
            }
        })
        input.change(function() {
            label.removeClass('checked');
            $(this).parents('label').addClass('checked');
            input.removeAttr('checked');
            $(this).prop("checked", true)
        })
    });
    $('[role=checkbox]').each(function() {
        var input = $(this).find('input[type="checkbox"]');
        input.each(function() {
            if ($(this).attr('checked')) {
                $(this).parents('label').addClass('checked');
                $(this).prop("checked", true);
            }
        });
        input.change(function() {
            $(this).parents('label').toggleClass('checked');
        });
    });
    /**单选多选end */
    // 弹窗 
    $('.windows-e1').hide();
    $('body').on('click', '.myfancy-e1', function() {
        var $win = $(this).data('id');
        $('.windows-e1').addClass('on')
        if ($($win).length > 0) {
            $($win).stop().fadeIn();
        }
    })
    $('body').on('click', '.js-pop-close', function() {
        var $win = $(this).parents('.js-pop');
        $($win).stop().fadeOut();
    })

})
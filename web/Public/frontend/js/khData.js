$(function() {

    var itemIndex = 0;

    var tabLoadEndArray = [false, false, false];
    var tabLenghtArray = [28, 15, 47];
    var tabScroolTopArray = [0, 0, 0];

    // dropload
    var dropload = $('.khfxWarp').dropload({
        scrollArea: window,
        domDown: {
            domClass: 'dropload-down',
            domRefresh: '<div class="dropload-refresh">上拉加载更多</div>',
            domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
            domNoData: '<div class="dropload-noData">已无数据</div>'
        },
        loadDownFn: function(me) {
            setTimeout(function() {
                if (tabLoadEndArray[itemIndex]) {
                    me.resetload();
                    me.lock();
                    me.noData();
                    me.resetload();
                    return;
                }
                var result = '';
                for (var index = 0; index < 10; index++) {
                    if (tabLenghtArray[itemIndex] > 0) {
                        tabLenghtArray[itemIndex]--;
                    } else {
                        tabLoadEndArray[itemIndex] = true;
                        break;
                    }
                    if (itemIndex == 0) {
                        result
                            += '' +
                            '    <li>' +
                            '      <a href="" class="con">' +
                            '      <div class="pic">' +
                            '        <img src="images/news.png" alt="">' +
                            '        </div>' +
                            '        <div class="right">' +
                            '        <h3 class="tit">平台规则调整通知</h3>' +
                            '        <div class="info"> ' +
                            '      <div class="span">系统公告</div>' +
                            '      <div class="time">2024.06.05</div>' +
                            '    </div>' +
                            '      <div class="desc">平台规则调整通知平台规则调整通知平台规则调整通知平台规则调整通知</div>' +
                            '      </div>' +
                            '      </a>' +
                            '      </li>';
                    } else if (itemIndex == 1) {
                        result
                            += '' +
                            '    <li>' +
                            '      <a href="" class="con">' +
                            '      <div class="pic">' +
                            '        <img src="images/news.png" alt="">' +
                            '        </div>' +
                            '        <div class="right">' +
                            '        <h3 class="tit">平台规则调整通知</h3>' +
                            '        <div class="info"> ' +
                            '      <div class="span">系统公告</div>' +
                            '      <div class="time">2024.06.05</div>' +
                            '    </div>' +
                            '      <div class="desc">平台规则调整通知平台规则调整通知平台规则调整通知平台规则调整通知</div>' +
                            '      </div>' +
                            '      </a>' +
                            '      </li>';
                    } else if (itemIndex == 2) {
                        result
                            += '' +
                            '    <li>' +
                            '      <a href="" class="con">' +
                            '      <div class="pic">' +
                            '        <img src="images/news.png" alt="">' +
                            '        </div>' +
                            '        <div class="right">' +
                            '        <h3 class="tit">平台规则调整通知</h3>' +
                            '        <div class="info"> ' +
                            '      <div class="span">系统公告</div>' +
                            '      <div class="time">2024.06.05</div>' +
                            '    </div>' +
                            '      <div class="desc">平台规则调整通知平台规则调整通知平台规则调整通知平台规则调整通知</div>' +
                            '      </div>' +
                            '      </a>' +
                            '      </li>';
                    }
                }
                $('.khfxPane').eq(itemIndex).append(result);
                me.resetload();
            }, 500);
        }
    });


    $('.tabHead span').on('click', function() {

        tabScroolTopArray[itemIndex] = $(window).scrollTop();
        var $this = $(this);
        itemIndex = $this.index();
        $(window).scrollTop(tabScroolTopArray[itemIndex]);

        $(this).addClass('active').siblings('.tabHead span').removeClass('active');
        $('.tabHead .border').css('left', $(this).offset().left + 'px');
        $('.khfxPane').eq(itemIndex).show().siblings('.khfxPane').hide();

        if (!tabLoadEndArray[itemIndex]) {
            dropload.unlock();
            dropload.noData(false);
        } else {
            dropload.lock('down');
            dropload.noData();
        }
        dropload.resetload();
    });
});
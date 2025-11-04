function viewLayer(url, obj)
{
    layer.open({
        type: 2,
        title: obj.attr('title'),
        maxmin: true,
        shadeClose: false, //点击遮罩关闭层
        area : ['80%' , '70%'],
        content: url
    });
}
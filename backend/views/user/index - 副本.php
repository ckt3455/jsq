
<div id="map" style="width: 100px;height: 100px"></div>
<script src="//webapi.amap.com/maps?v=1.3&key=3ebd8b1d58b4eab6cb954712a09dc1f5"></script>
<script>
    //需要类型库要获取response的分值start
    var telnum=""
    var params = document.location.search.substr(1).split('&');
    if (typeof(params[1])=="string"){
        telnum = params[1].substring(2);
    }
    //需要类型库要获取response的分值end
    var User = 'smsb';
    var wtlx_id=""
    var zrdw_id;
    var photo_name="";


    var zuobiao_lng;
    var zuobiao_lat;

    var mapObj= new AMap.Map('map', {
        resizeEnable: true,
        center: [121.138398, 30.972688],
        zoom: 15
    });
    var marker=null;

    mapObj.plugin('AMap.Geolocation', function () {
        geolocation = new AMap.Geolocation({

            enableHighAccuracy: true, // 是否使用高精度定位，默认:true
            timeout: 10000,           // 超过10秒后停止定位，默认：无穷大
            maximumAge: 0,            // 定位结果缓存0毫秒，默认：0
            convert: true,            // 自动偏移坐标，偏移后的坐标为高德坐标，默认：true
            showButton: true,         // 显示定位按钮，默认：true
            buttonPosition: 'LB',     // 定位按钮停靠位置，默认：'LB'，左下角
            buttonOffset: new AMap.Pixel(10, 20), // 定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
            showMarker: true,         // 定位成功后在定位到的位置显示点标记，默认：true
            showCircle: true,         // 定位成功后用圆圈表示定位精度范围，默认：true
            panToLocation: true,      // 定位成功后将定位到的位置作为地图中心点，默认：true
            zoomToAccuracy:true       // 定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
        });
        mapObj.addControl(geolocation);
        geolocation.getCurrentPosition();
        //alert(geolocation.getCurrentPosition);
        AMap.event.addListener(geolocation, 'complete', onComplete); // 返回定位信息
        AMap.event.addListener(geolocation, 'error', onError);       // 返回定位出错信息
    });

    function onComplete(obj){

        $('#wtdz').val(obj.formattedAddress.replace('浙江省', '').replace('温州市', ''))

    }

    function onError(obj) {

        var pt= mapObj.getCenter();
        marker = new AMap.Marker({
            icon: "./img/poi-marker-default.png",
            position: pt,
            offset: new AMap.Pixel(-26, -60)
        });
        marker.setMap(mapObj);

        var zuobiao=bd_encrypt(pt.lng,pt.lat)

        zuobiao_lng=zuobiao.bd_lng;
        zuobiao_lat=zuobiao.bd_lat;

        mapObj.on('zoomend', logMapinfo);
        mapObj.on('mapmove', logMapMove);

    }



    //高德坐标转百度（传入经度、纬度）
    function bd_encrypt(gg_lng, gg_lat) {
        var X_PI = Math.PI * 3000.0 / 180.0;
        var x = gg_lng, y = gg_lat;
        var z = Math.sqrt(x * x + y * y) + 0.00002 * Math.sin(y * X_PI);
        var theta = Math.atan2(y, x) + 0.000003 * Math.cos(x * X_PI);
        var bd_lng = z * Math.cos(theta) + 0.0065;
        var bd_lat = z * Math.sin(theta) + 0.006;
        return {
            bd_lat: bd_lat,
            bd_lng: bd_lng
        };
    }
    //显示地图层级与中心点信息
    function logMapinfo(){
        var zoom = mapObj.getZoom(); //获取当前地图级别
        var center = mapObj.getCenter(); //获取当前地图中心位置
        addMarker(center);
        //console.log(zoom);
        // console.log(center);
    };
    function addMarker(pt) {
        if (marker) {
            marker.setMap(null);
            marker = null;
        }
        marker = new AMap.Marker({
            icon: "./img/poi-marker-default.png",
            position: pt,
            offset: new AMap.Pixel(-26, -60)
        });
        marker.setMap(mapObj);

        //var lnglatXY = [pt.lng,pt.lat]; 改变 marker.setIcon(icon);
        regeocoder(pt)
        var zuobiao=bd_encrypt(pt.lng,pt.lat)
        //console.log(zuobiao)
        zuobiao_lng=zuobiao.bd_lng;
        zuobiao_lat=zuobiao.bd_lat;

    }

    function logMapMove(){
        var zoom = mapObj.getZoom(); //获取当前地图级别
        var center = mapObj.getCenter(); //获取当前地图中心位置

        if (!marker) {

            marker = new AMap.Marker({
                icon: "../img/poi-marker-default.png",
                position: center,
                offset: new AMap.Pixel(-26, -60)
            });
            marker.setMap(mapObj);
        }else{

            marker.setPosition(center)
        }

        regeocoder(center)
        var zuobiao=bd_encrypt(center.lng,center.lat)
        //console.log(zuobiao)
        zuobiao_lng=zuobiao.bd_lng;
        zuobiao_lat=zuobiao.bd_lat;



    }

    //绑定地图移动与缩放事件
    mapObj.on('moveend', logMapinfo);
    // mapObj.on('zoomend', logMapinfo);
    mapObj.on('mapmove', logMapMove);
    function regeocoder(loc) {  //逆地理编码


        var geocoder = new AMap.Geocoder({

            radius: 1000,

            extensions: "all"

        });

        geocoder.getAddress(loc, function(status, result) {

            if (status === 'complete' && result.info === 'OK') {

                //  console.dir(result);

                geocoder_CallBack(result);

            }

        });

        // var marker = new AMap.Marker({  //加点

        //  map: mapObj,

        // position: loc

        //});

        //mapObj.setFitView();

    }

    function geocoder_CallBack(data) {
        // alert("dizhi")
        // var address = data.regeocode.formattedAddress; //返回地址描述
        $('#wtdz').val(data.regeocode.formattedAddress.replace('浙江省', '').replace('温州市', ''));
        //document.getElementById("result").innerHTML = address;

    }
    Dropzone.autoDiscover = false;

</script>
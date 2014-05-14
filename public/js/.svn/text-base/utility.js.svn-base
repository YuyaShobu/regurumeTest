//住所経緯度取得
function ajax_search(){
    $("#search_results").show();
    var search_val=$("#shop_name").val();
    $.post("/shop/ajaxgetshoplist/", {shopname : search_val}, function(data){
        if (data.length>0){
            $("#search_results").html(data);
        }
    })
}

//店名選択
function SelectChange(obj) {
    var shopname
    var latitude
    var longitude
    //$('#searchshop').change(function(){
    $('#searchshop').click(function(){
        var obj = $("select[name='shop_id']").val();
        var term = obj.split( '_' );
         shopname = term[0];
         latitude = term[1];
         $("#shop_id").val(obj);
         //時々改行コードが入ってることがあるので対応
         var stringcheck = term[2].indexOf('\n');
         if (stringcheck == -1) {
             longitude = term[2];
         } else {
             longitude_n = term[2].split( '\n' );
             longitude = longitude_n[0];
         }
         var mapinstance = new google.maps.LatLng(latitude,longitude);
         map.panTo(mapinstance);
         // マーカーの生成
         var marker = createMarker(mapinstance);
         // 中心位置の移動
         map.setCenter(mapinstance);

    });
    //map.panTo(new google.maps.LatLng(latitude,longitude));
}

// Google Mapsマーカーの表示
function createMarker(loc) {

    var marker = new google.maps.Marker({
        position: loc,
        map: map
    });
    return marker;
}
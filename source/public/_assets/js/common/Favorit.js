var Favorit = {
    url : "/mypage/favorit",
    action : function( type , uid ,target ){
        var datas = {type:type,uid:uid};
        var $target = $(target);
        requestAjax.request(this.url+"/proc",datas,'POST','JSON',true,function(res){
            if( res.status == '1' ){
                if( res.data.fav_state == '1'){
                    $target.find('.sjwi-star').addClass('checked');
                }else{
                    $target.find('.sjwi-star').removeClass('checked');
                }
            }else{
                alert('스크랩 처리도중 오류가 발생했습니다.');
            }
        });
    }
}
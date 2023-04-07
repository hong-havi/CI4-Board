var comments;
$(function(){
    $(".bv-attach").attachs({
        'uptype' : 'bbs',
        'mode' : 'view',
        'submit_target' : '.bbs_upload'
    });

    var comments = $(".bv-comment").comment({
        'uptype' : 'bbs',
        'parent' : $("input[name='bbs_uid']").val(),
        'menu_uid' : $("input[name='menu_uid']").val(),
    });
    
});

function bbs_delete(muid,buid){
    if( confirm('삭제시 복구가 불가능합니다. 그래도 삭제하시겠습니까?') ){
        var datas = {buid:buid};
        requestAjax.request('/site/'+muid+'/bbs/proc/delete',datas,'POST','JSON',true,function(res){
            if( res.status == 1 ){
                location.href='/site/'+muid+'/bbs';
            }else{
                alert('글 삭제 도중 오류가 발생했습니다.');
            }
        });
    }
} 
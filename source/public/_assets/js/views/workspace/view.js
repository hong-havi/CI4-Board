var comments;
$(function(){
    $(".bv-attach").attachs({
        'uptype' : 'ws',
        'mode' : 'write',
        'submit_target' : '.bbs_upload',
        submit_func : function (filelists){
            var pj_idx = $("input[name='pj_idx']").val();
            var datas = {pj_idx:pj_idx,filelists:filelists};
            requestAjax.request('/site/644/workspace/proc/setFile',datas,'POST','JSON',true,function(res){
                
            });
        },
        delete_func : function(fuid){
            var pj_idx = $("input[name='pj_idx']").val();
            var filelists = $("input[name='bbs_upload']").val();
            var datas = {pj_idx:pj_idx,filelists:filelists};
            requestAjax.request('/site/644/workspace/proc/setFile',datas,'POST','JSON',true,function(res){
                
            });
        }
    });

    var comments = $(".bv-comment").comment({
        'uptype' : 'ws',
        'parent' : $("input[name='pj_idx']").val(),
        'menu_uid' : $("#muid").val(),
    });
    
    Workspace.Init($("#muid").val());    

    $(".bv-contents-body").ImgResize();
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
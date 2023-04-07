$(function(){
    Editor.set('.bw-editor');
    $(".bw-attach").attachs({
        'uptype' : 'ws',
        'mode' : 'write',
        'submit_target' : '.bbs_upload'
    });
    
    
    Workspace.Init($("#muid").val());
    Workspace.getGroup(2,'','cate1');
    $("select[name='p_type']").change();
});


function write_submit(save_type){
    var $target = $("#ws_write_form");
    var submit_url = $target.attr("action");
    var datas = $target.serializeObject();
    datas.content = editor.getData();
    requestAjax.request(submit_url,datas,'POST','JSON',true,function(res){
        if( res.status == 1 ){
            location.href=res.data.link;
        }else{
            alert('글 작성 도중 오류가 발생했습니다.');
        }
    });
}

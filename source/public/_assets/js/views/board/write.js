$(function(){
    Editor.set('.bw-editor');
    $(".bw-attach").attachs({
        'uptype' : 'bbs',
        'mode' : 'write',
        'submit_target' : '.bbs_upload'
    });
    
})

function write_submit(save_type){
    var $target = $("#bbs_write_form");
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

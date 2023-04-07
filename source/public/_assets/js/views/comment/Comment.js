/*!
 * Jquery Attach Form
 * attachs.js v0.0.1 
 * Hong sunghyun
 * 2020-08-25
 * 
 */

(function ( $ ) {
    
    $.fn.comment = function(options) {
        var $object;

        var aurl;

        var defaults = {
            'template' : 'default',
            'menu_uid' : 1,
            'uptype' : '',
            'parent' : 0
        };


        var settings = $.extend( {}, defaults, options );
        return this.each(function() {
            $object = $(this);
            $submit_target = $(settings.submit_target);
            aurl = '/site/'+settings.menu_uid+'/comment';
            init();
        });

        function init(){
            var datas = {template:settings.template,uptype:settings.uptype,parent:settings.parent};
            requestAjax.loading_flag = false;
            requestAjax.request(aurl,datas,'GET','HTML',true,function(html){
                var $html = $(html);
                $object.html($html);
                writeAction('cmt',$html,1);
                UserInfoPop();
                listAction($html.find(".comment-list"));
                setCount();
            });
            requestAjax.loading_flag = true;
        }

        function findMention($content){
            var $mentions = $content.find(".mention");
            var len = $mentions.length;
            var unos = [];
            var k = 0;
            for( i = 0 ; i < len ; i++ ){
                var uno = $mentions.eq(i).attr("data-user-id");
                if( uno ){
                    unos[k] = uno;
                    k++
                }
            }
            
            return unos;
        }


        function listload(){
            var datas = {uptype:settings.uptype,parent:settings.parent};
            requestAjax.request(aurl+"/list",datas,"GET","HTML",true, function(html){
                var $html = $(html);
                $(".comment-list").html($html);
                UserInfoPop();
                listAction($html);
                setCount();
            });
        }

        function listAction( $list ){
            var replay_btn = document.querySelectorAll('.comment_reply_btn');
            [].forEach.call(replay_btn,function(col){
                col.addEventListener("click",function(){
                    var $btnarea = $(this).parent(".cmt-lc-btn");
                    var cuid = $btnarea.attr('cuid');
                    var depth = $btnarea.attr("depth");
                    openWriteForm('replay',cuid, (parseInt(depth+1)));
                }); 
            });
            
            var modify_bnt = document.querySelectorAll('.comment_modify');
            [].forEach.call(modify_bnt,function(col){
                col.addEventListener("click",function(){
                    var $btnarea = $(this).parent(".cmt-lc-btn");
                    var cuid = $btnarea.attr('cuid');
                    var depth = $btnarea.attr("depth");
                    openWriteForm('modify',cuid,depth);
                }); 
            });
            
            var delete_btn = document.querySelectorAll('.comment_delete');
            [].forEach.call(delete_btn,function(col){
                col.addEventListener("click",function(){
                    var $btnarea = $(this).parent(".cmt-lc-btn");
                    var cuid = $btnarea.attr('cuid');
                    var depth = $btnarea.attr("depth");
                    cdelete(cuid,depth);
                }); 
            });
        }

        function openWriteForm( mode, cuid, depth ){
            $(".cmtc-warea").html('');
            requestAjax.loading_flag = false;
            var datas ={template:settings.template,mode:mode,parent:cuid,depth:depth,uptype:settings.uptype};
            var $objectc = $(".cmtc-wform-"+cuid);
            requestAjax.request(aurl+"/"+mode,datas,'GET','HTML',true,function(html){
                var $html = $(html);
                $objectc.html($html);
                writeAction('cmtc',$html,depth);
            });
            requestAjax.loading_flag = true;

        }

        function writeAction(target,$html, depth){

            Editor.set('.'+target+'-editor');
            if( depth == 1){
                $("."+target+"-attach").attachs({
                    'uptype' : 'comment',
                    'mode' : 'write',
                    'submit_target' : '.'+target+'_upload'
                });
            }
            $html.find(".cmt_write_btn").on("click",function(){
                var $target = $html.find("#cmt-write-form");
                var datas = $target.serializeObject();
                datas.cmt_content = editor.getData();
                var $content = $(datas.cmt_content);
                datas.cmt_mentions = findMention($content);
                var submit_url = $html.find("form[name='cmt-write-form']").attr("action");
                requestAjax.request(submit_url,datas,'POST','JSON',true,function(res){
                    if( res.status == 1 ){
                        alert('등록되었습니다.');
                        if( target =='cmt'){
                            editor.setData('');
                            listload();
                        }else{
                            listload();
                        }
                    }else{
                        alert('댓글 처리도중 오류가 발생했습니다.');
                    }
                });
            });
        }

        function openReplay( cuid ){

        }

        function openModify( cuid ){

        }

        function setCount(){
            var len = $object.find('.comment-list .cmt-l-content').length;
            $object.find('.cmt-count').html('댓글 '+len+'개');
        }

        function cdelete(cuid,depth){
            if( confirm('삭제시 복구가 불가능합니다. 그래도 삭제하시겠습니까?') ){
                var datas = {cuid:cuid,depth:depth,uptype:settings.uptype};
                requestAjax.request(aurl+"/delete",datas,'POST','JSON',true,function(res){
                    if(res.status == 1){
                        alert('삭제되었습니다.');
                        listload();
                    }else{
                        alert('삭제 처리도중 오류가 발생했습니다.');
                    }
                });
            }
        }

    }
}( jQuery ));

var CommentAct = {
    openReplay : function(cuid,depth){

    },
    openEdit : function(cuid,depth){

    },
    delete : function(cuid,depth){

    }
}
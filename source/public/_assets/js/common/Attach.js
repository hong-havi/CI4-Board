/*!
 * Jquery Attach Form
 * attachs.js v0.0.1 
 * Hong sunghyun
 * 2020-08-25
 * 
 */

(function ( $ ) {

    $.fn.attachs = function(options) {
        var $object;
        var $allObj;
        var $fzone;
        var tmpk = {
            'm':0
        };

        var $submit_target;

        var tempcode;

        var upinfo = {'size':0,'cnt':0};
        var checkCnt = {'set':0,'end':0,};
        var drag_flag = false;

        var defaults = {
            'mode' : 'view',
            'uptype' : 'bbs',
            'maxsize' : 524288000, //byte
            'submit_target' : '',
            'submit_func' : '',
            'delete_func' : ''
        };


        var settings = $.extend( {}, defaults, options );
        return this.each(function() {
            $object = $(this);
            $submit_target = $(settings.submit_target);
            formset();
        });

        function formset(){
            var datas = {mode:settings.mode};
            requestAjax.request('/common/attach/initform',datas,'GET','HTML',true,function(html){
                $allObj = $(html);
                $object.html($allObj);

                $allObj.find(".uploadbtn").on('click',function(){
                    openUpload();
                });
                $allObj.find(".Att-refreash").on('click',function(){
                    listLoad();
                });
               
                listLoad();

            });
        }

        function openUpload(gidx){
            upinfo = {size:0,cnt:0};
            checkCnt = {set:0,end:0};

            gidx = (gidx) ? gidx : 0;
            var datas = {maxsize:getfileSize(settings.maxsize),gidx:gidx};
            
            requestAjax.loading_flag = false;
            requestAjax.request('/common/attach/upload',datas,'GET','HTML',true,function(html){
                var $AttachModal = $(html);
                getModal($AttachModal,'cmmodal-attach-upload');

                var $AttachBtn = $AttachModal.find('#fzone-fileBtn');
                $fzone = $AttachModal.find(".fzone-list");
                tempcode = $AttachModal.find("input[name='attach_tempcode']").val();
                $AttachBtn.on('change',function(){
                    var files = $(this)[0].files;
                    if (files.length > 0 ){
                        upload_proc(files,gidx);
                    }
                });
                $fzone.on({
                    'drop' : function(event){
                        setfzone(event,'drop');
                        event.preventDefault();
                    },
                    'dragover' : function(event){
                        setfzone(event,'over');
                        event.preventDefault();
                    },
                    'dragleave' : function(event){
                        setfzone(event,'leave');
                    }
                });
                $AttachModal.find("#attach-submit").on("click",function(){
                    submit();
                });
            });            
            requestAjax.loading_flag = true;
        }

        function upload_proc( files , gidx ){

            $flist_div = $fzone.find("ul");
            var fcnt = files.length;
            checkCnt.set += fcnt;
            for( i = 0 ; i < fcnt ; i++ ){
                var file = files[i];
                upload_ajax(file,gidx);
            }
            files.value = '';
        }
        
        function upload_ajax(file,gidx){
            if( file.size > settings.maxsize ){
                alert(file.name + " 은 업로드 제한 용량 초과입니다.");
                return false;
            }
            var $tpl = setProgressbar(file);
            var $ptarget = $tpl.find('.progress-bar');
            $flist_div.append($tpl);

            var formData = new FormData();
            formData.append('upfile', file);
            formData.append('tempcode',tempcode);
            formData.append('uptype',settings.uptype);
            formData.append('gidx',gidx);
            $.ajax({
                url : '/common/attach/upload',
                data : formData,
                type : "POST",
                contentType : false,
                processData : false,
                xhr : function(){
                    var xhr = $.ajaxSettings.xhr();
                    xhr.upload.onprogress = function(e) { //progress 이벤트 리스너 추가
                        var percent = e.loaded * 100 / e.total;                                        
                        if( percent > 100 ){
                            percent = 100;
                        }
                        $ptarget.html(percent+"%");
                        $ptarget.css("width",percent+"%");
                    };
                    return xhr;                        
                },
                error : function(e){
                    var message = (e.responseJSON.messages.error) ? e.responseJSON.messages.error : "파일 업로드에 실패했습니다. [9999]";
                    $ptarget.html("업로드 실패");
                    $ptarget.css("width","100%");
                    $ptarget.addClass("bg-danger");
                    alert(message);
                    
                },
                success : function(res){
                    if( res.status == 1 ){
                        var fidx = res.data.fidx;
                        setInput(fidx,'add');
                        $ptarget.html("업로드 완료");
                        var $delbtn = $tpl.find('[hidden]');
                        $delbtn.attr("data",fidx)
                        $delbtn.removeAttr('hidden');

                        upinfo.size = upinfo.size + file.size;
                        upinfo.cnt++;
                        $(".fzone-list-info").html(getfileSize(upinfo.size) + " / " + upinfo.cnt + "개");
                    }else{
                        var message = (res.message) ? res.message : "파일 업로드에 실패했습니다. [9999]";
                        $ptarget.html("업로드 실패");
                        $ptarget.css("width","100%");
                        $ptarget.addClass("bg-danger");
                        alert(message);
                    }
                },
                complete : function(){
                    checkCnt.end++;
                }

            })
        }
        
        function setProgressbar( file ){
            var tpl = "<li>";
                tpl += "    <div class=\"fl-text\">"+file.name+" <span class=\"fl-size\">"+getfileSize(file.size)+"</span> <a href=\"javascript:;\" data=\"\" onclick=\"Attach.delUpdata(this)\" hidden><i class=\"sjwi-delete\"></i></a></div>";
                tpl += "    <div class=\"status-bar\">";
                tpl += "        <div class=\"progress mb-3\">";
                tpl += "        <div class=\"progress-bar progress-bar-striped fzone-file-progress\" data=\""+checkCnt.set+"\" role=\"progressbar\" style=\"width: 0%\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\">0%</div>";
                tpl += "        </div>";
                tpl += "    </div>";
                tpl += "</li>";
            var $li = $(tpl);
            return $li;
        }

        function setInput (fidx,type){
            var $input_target = $("input[name='upoad_modal_fidx']");
            var v = $input_target.val();
            var arr = [];
            if( v ){
                arr = v.split(",");
            }
            switch( type ){
                case 'add' :
                    arr.push(fidx);
                    break;
                case 'del' :
                    var k = arr.indexOf(fidx);
                    arr.splice(k,1);
                    break;
            }
            $input_target.val(arr.join(','));
        }

       function setfzone(evt,opt){
            switch( opt ){
                case 'over' :
                    if( drag_flag !== true ){
                        $fzone.css("border","3px solid #ddd");
                        drag_flag = true;
                    }
                    break;
                case 'leave' :
                    $fzone.css("border","1px solid #ddd");
                    drag_flag = false;
                    break;
                case 'drop' :
                    upload_proc( evt.originalEvent.dataTransfer.files );
                    $fzone.css("border","1px solid #ddd");
                    drag_flag = false;
                    break;
            }
        }
        
        function submit(){            
            if( checkCnt.set > checkCnt.end ){
                alert('아직 업로드중인 파일이 존재합니다. 업로드 완료 후 첨부가 가능합니다.');
                return false;
            }

            var retval = $("input[name='upoad_modal_fidx']").val();
            if( retval ){                
                var $target = $submit_target;

                var v = $target.val();
                var arr = [];
                if( v ){
                    arr = v.split(",");
                }

                var ret_arr = retval.split(",");    

                var real_val = arr.concat(ret_arr);
                real_val = real_val.join(",");
                $target.val(real_val);                
            }
            closeModal();
            listLoad();
            if( settings.submit_func ){
                settings.submit_func(real_val);
            }

        }

        function listLoad(){
            var fnos = $submit_target.val();

            if( fnos ){
                var datas = {fnos:fnos,mode:settings.mode};
                requestAjax.loading_flag = false;
                requestAjax.request('/common/attach/list',datas,'POST','JSON',true,function(res){
                    var $item_tpl = $(res.data.tpl);
                    $allObj.find(".att-table tbody").html($item_tpl);
                    $('[data-toggle="tooltip"]').tooltip();

                    $item_tpl.find(".atta-del").on("click",function(){
                        var fuid = $(this).attr("fuid");
                        attDel(fuid);
                    });
                    $item_tpl.find(".atta-edit").on("click",function(){
                        var fuid = $(this).attr("fuid");
                        attEdit(fuid);
                    });
                    $item_tpl.find(".atta-upload").on("click",function(){
                        var gidx = $(this).attr("gidx");
                        openUpload(gidx);
                    });
                    $item_tpl.find(".atta-list").on("click",function(){
                        var gidx = $(this).attr("gidx");
                        attClist(gidx);
                    });
                });
                requestAjax.loading_flag = true;
            }
        }

        function attDel(fuid){
                
            var $input_target = $submit_target;
            var v = $input_target.val();
            var arr = [];
            if( v ){
                arr = v.split(",");
            }
            var k = arr.indexOf(fuid);
            arr.splice(k,1);
            $input_target.val(arr.join(','));
            listLoad();

            if( settings.delete_func ){
                settings.delete_func(fuid);
            }
        }

        function attEdit(fuid){
            var datas = {fuid:fuid};
            requestAjax.request('/common/attach/descedit',datas,'GET','HTML',true,function(html){
                var $att_edit = $(html);
                getModal($att_edit,'cmmodal-attach-upload');
                $att_edit.find("#att-edit-btn").on("click",function(){
                    attEditsubmit();
                });
            });
        }
        
        function attEditsubmit(){
            var FormDatas = new FormData($("#fedit-form")[0]);
            requestAjax.request('/common/attach/descedit',FormDatas,'FORM','JSON',true,function(res){
                if( res.status == '1' ){
                    closeModal();
                }else{
                    alert('처리도중 오류가 발생되어 변경에 실패했습니다.');
                }
            });
            listLoad();
        }
        
        function attClist (gidx){
            var $target = $("tr[gdata='fg"+gidx+"'][child]");
            var display = $target.css("display");
            if( display == 'none' ){
                $target.show();
            }else{
                $target.hide();
            }
        }
    }
}( jQuery ));

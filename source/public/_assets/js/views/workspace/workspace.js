var Workspace = {
    setgroup : [],
    muid : 0,
    pj_idx : 0,


    Init : function( muid ){
        this.muid = muid;
    },

    getGroup : function(depth,ptagname,tagname){		
		if( ptagname ){
			var pno = $("select[name='"+ptagname+"'] option:selected").attr("code");
		}

		var datas = {depth:depth,pno:pno};
		requestAjax.request('/info/group/list',datas,'GET','JSON',true,function(res){
                
            var $target = $("select[name='"+tagname+"']");
            $target.find("option").remove();
            $target.append("<option value=''>::: 본부/팀 선택 :::</option>");

            var lists = res.data.glists;
            var len = lists.length;
            for( i = 0 ; i < len ; i++ ){
                var $data = lists[i];
                $target.append("<option value='"+$data.name+"' code='"+$data.uid+"'>"+$data.name+"</option");
            }
            
            var tmpvalue = $target.attr("tmpvalue");

            if( tmpvalue ){
                $target.val(tmpvalue);
                $target.change();
            }
        });
	
    },

    getPwtype : function(depth,pno,target){
        var datas = {depth:depth,pno:pno};
        requestAjax.request('/site/'+this.muid+'/workspace/getPwtype',datas,'GET','JSON',true,function(res){
            Workspace.w_type_info = res.data.wlist;

            var $target = $("select[name='"+target+"']");
            $target.find("option").remove();
            $target.append("<option value=''>::: 유형선택 :::</option>");
    
            var lists = Workspace.w_type_info;
            $.each(lists, function (key , info){
                $target.append("<option value='"+info.idx+"'>"+info.name+"</option>");
            });
            
            var tmpvalue = $target.attr("tmpvalue");

            if( tmpvalue ){
                $target.val(tmpvalue);
                $target.change();
            }
        });
    },

    addLink : function( type , data ){
        switch(type){
            case 'link' :
                var tpl = "<li><input type=\"text\" class=\"input-st3 col-sm-8\" name=\"sv_links_link[]\" value=\"\" /> <a href=\"javascript:;\" onclick=\"Workspace.delLink(this)\"><i class=\"sjwi-close_bold\"></i></a></li>";
                break;
            case 'pj_idx' :
                var tpl = "<li><input type=\"hidden\" class=\"input-st3 col-sm-8\" name=\"sv_links_pj[]\" value=\""+data.pj_idx+"\" />";
                    tpl += "<span>#"+data.pj_idx+". "+data.subject+"</span> ";
                    tpl += "<a href=\"javascript:;\" onclick=\"Workspace.delLink(this)\"><i class=\"sjwi-close_bold\"></i></a></li>";
                break;
        }

        $(".workspace-link-lists ul").append(tpl);
        closeModal();
    },

    delLink : function(target){
        $(target).parent('li').remove();
    },

    openFindWP :function(type,page){
        var sec_key = "";
        var sec_val = "";
        var page = (page) ? page : 1;

        if( type == 'search' ){
            var $form = $("#ws-find-form");
            sec_key = $form.find("select[name='sec_key']").val();
            sec_val = $form.find("input[name='sec_val']").val();
        }

        var datas = {sec_key:sec_key,sec_val:sec_val,page:page};
        requestAjax.request('/site/'+this.muid+'/workspace/findWork',datas,'GET','HTML',true,function(html){
            getModal(html,'cmmodal-ws-findwork','',{size:'modal-lg'});
        });
    },

    saveInfo : function(target){
        
        var $target = $(target);
        var datas = $target.serializeObject();        
        requestAjax.request('/site/'+this.muid+'/workspace/proc/infosave',datas,'POST','JSON',true,function(res){

            if(res.status == '1' ){
                alert('수정완료');
            }else{
                alert('수정도중 오류가 발생했습니다.');
            }
        });
    },

    openPjlog : function(pj_idx){
        var datas = {type:'one',pj_idx:pj_idx};
        requestAjax.request('/site/644/workspace/loglist_modal',datas,'GET','HTML',true,function(html){
            getModal(html,'cmmodal-ws-pjlog','common_modal',{size:'modal-lg'});            
        });
    },

    favorit : function( pj_idx ,target ){
            var datas = {pj_idx:pj_idx};
            var $target = $(target);
            requestAjax.request('/site/644/workspace/proc/favorit',datas,'POST','JSON',true,function(res){
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


var ws_worker = {
    add : function(pj_idx,type){
        if( type == 'lists' ){
            var lists = $(".waf-auser-form").find("input[name='sender_list_99']").val();
            if( !lists ){
                alert('추가할 인원을 선택해 주세요.');
            }
        }else{
            var lists = "";
        }
        var datas = {pj_idx:pj_idx,type:type,lists:lists};
        requestAjax.request('/site/644/workspace/worker/add',datas,'POST','JSON',true,function(res){
            if( res.status == '1' ){
                ws_worker.loadTime();
            }else{
                alert('추가도중 오류가 발생했습니다');
            }
        });
    },

    openAdd : function(target,pj_idx){
        $(target).popover({
            'html':true,
            'content': function(e){
                var datas = {pj_idx:pj_idx};
                requestAjax.loading_flag = false;
                var res = requestAjax.request('/site/644/workspace/worker/addform',datas,'GET','HTML');
                requestAjax.loading_flag = true;
                return res;
            }
        });
        $(target).popover('show');
    },

    wt_idx : 0,
    openTime : function (wt_idx,wtdate){
        ws_worker.wt_idx = wt_idx;
        wtdate = (wtdate) ? wtdate : "";
        var datas = {wt_idx:wt_idx,wtdate:wtdate};
        requestAjax.request('/site/644/workspace/worker/timeform',datas,'GET','HTML',true,function(html){
            getModal(html,'cmmodal-ws-workertime');
            datepickerset('.datepicker',ws_worker.reOpenTime);            
        });
    },

    reOpenTime : function(wtdate){
        ws_worker.openTime(ws_worker.wt_idx,wtdate);
    },
    saveTime : function(){
        var $target = $("#ws_worker_tform");
        var datas = $target.serializeObject();
        requestAjax.request('/site/644/workspace/worker/savetime',datas,'POST','JSON',true,function(res){
            if( res.status == '1' ){
                closeModal();
                ws_worker.loadTime();
            }else{
                alert('처리도중 오류가 발생했습니다.');
                closeModal();
                ws_worker.loadTime();
            }
        });
    },

    loadTime : function(pj_idx){
        pj_idx = (pj_idx) ? pj_idx : $("input[name='pj_idx']").val();
        var datas = {pj_idx:pj_idx};
        requestAjax.request('/site/644/workspace/worker/list',datas,'GET','HTML',true,function(html){
            $(".wp-worker-area").html(html);
        });
    },

    del : function (wt_idx){
        if( confirm('삭제시 복구가 불가능합니다. 담당자 리스트에서 삭제하시겠습니까?') ){
            var datas = {wt_idx:wt_idx};
            requestAjax.request('/site/644/workspace/worker/delete',datas,'POST','JSON',true,function(res){
                if( res.status == 1){
                    alert('삭제완료');
                    ws_worker.loadTime();
                }else{
                    alert('삭제도중 오류가 발생했습니다.');
                }
            });
        }
    },
    modify : function(wt_idx){
        var $form = $(".worker-info-"+wt_idx).find("input, select");
        var datas = $form.serializeObject();
        datas.wt_idx = wt_idx;
        requestAjax.request('/site/644/workspace/worker/modify',datas,'POST','JSON',true,function(res){
            if( res.status == 1){
                alert('변경완료');
            }else{
                alert('삭제도중 오류가 발생했습니다.');
            }
        });        
    }

}

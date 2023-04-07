var Sender ={

    submit_target : {'name':"","type":""},

    open : function( target, type ){

        this.submit_target.name = target;
        this.submit_target.type = type;

        var datas = {};    
        requestAjax.request('/common/sender/find',datas,'GET','HTML',true,function(html){
            getModal(html,'cmmodal-sender-find');
        });

    },

    find : function( val ){
        if( val ){
            $(".smd-tree ul, .smd-tree li").hide();

            $(".smd-tree").find(".group-icon").removeClass('sjwi-plus');
            $(".smd-tree").find(".group-icon").addClass('sjwi-minus');

            var $target_input = $(".smd-tree input[name='smd-target'][sec-data*='"+val+"']");
            var len = $target_input.length;

            for( i = 0 ; i < len ; i++ ){
                var $target = $target_input.eq(i);
                $target.parents('.smd-tree-data').show();
            }

        }else{
            $(".smd-tree ul, .smd-tree li").show();           
        }

    },

    group_act : function($t){
        var $ti = $t.children(".group-icon");

        if( $ti.hasClass('sjwi-plus') ){ //펼치기 +
            $ti.removeClass('sjwi-plus');
            $ti.addClass('sjwi-minus');
            
            $t.parent(".smd-tree-data").children(".smd-tree-data").show();
        } else { //숨기기 - 
            $ti.removeClass('sjwi-minus');
            $ti.addClass('sjwi-plus');
            
            $t.parent(".smd-tree-data").children(".smd-tree-data").hide();
        }
    },

    checked : function(t){
        var $target = $(t);
        var v = $target.val();
        var checked = $target.prop("checked");

        if( checked == true ){
            this.checked_on(v);
        }else{
            this.checked_off(v);
        }
    },

    checked_on : function( v ){
        var value_arr = v.split("|");
        var tpl = "<li data=\""+v+"\">"+value_arr[1]+" <a href=\"javascript:;\" onclick=\"Sender.checked_off('"+v+"')\" class=\"del\">X</a></li>";
        $(".smd-selector-list").append(tpl);
    },

    checked_off : function( v ){
        var value_arr = v.split("|");
        $(".smd-selector-list").find("li[data='"+v+"']").remove();
        $("input[name='smd-target'][value='"+v+"']").prop("checked",false);
    },

    submit : function(){

        var $append_lists = $(".smd-selector-list li");
        var append_len = $append_lists.length;

        var $forms = $("."+this.submit_target.name+"[data='"+this.submit_target.type+"']");
        var $lists = $forms.find(".sender-list");
        
        var $input = $forms.find("input[name='sender_list_"+this.submit_target.type+"']");
        var input_data = $input.val();
        input_data = (input_data) ? input_data.split(",") : [];

        for( var i = 0 ; i < append_len ; i++ ){
            var data = $append_lists.eq(i).attr("data");
            var value_arr = data.split("|");
            var check_len = $lists.find("li[data='"+data+"']").length;
            if( check_len == 0 ){
                var tpl = "<li data=\""+data+"\">"+value_arr[1]+" <a href=\"javascript:;\" onclick=\"Sender.submit_del(this,'" + value_arr[0] + "')\" class=\"del\">X</a></li>";
                $lists.append(tpl);
                input_data.push(value_arr[0]);
            }
        }

        input_data = input_data.join(",");
        $input.val(input_data);

        closeModal();
        
    },

    submit_del : function( target, data ){
        var $input_target = $(target).parents(".sender-form").find("input[type='hidden']");
        var v = $input_target.val();
        var v_arr = v.split(",");
        v_arr.splice(v_arr.indexOf(data),1);

        $input_target.val(v_arr.join(","));        
        $(target).parent("li").remove();
    },

    openList : function(cate,bbs_uid){
        var datas = {cate:cate,bbs_uid:bbs_uid};
        requestAjax.request('/common/sender/list',datas,'GET','HTML',true,function(html){
            getModal(html,'cmmodal-sender-list');
        });
    },

    toggleModSender : function(target){
        var $target = $(target);
        if( $target.css("display") == 'none' ){
            $target.show();
        }else{
            $target.hide();
        }
    },
    submit_modify : function(cate,bbs_uid,muid,target){
        var sender_list_1 = $("input[name='"+target+"_1']").val();
        var sender_list_2 = $("input[name='"+target+"_2']").val();
        var datas = {muid:muid,cate:cate,bbs_uid:bbs_uid,sender_list_1:sender_list_1,sender_list_2:sender_list_2};
        requestAjax.request('/common/sender/add',datas,'POST','JSON',true, function(res){
            if ( res.status == '1' ){
                location.reload();
            }else{
                alert('데이터 처리도중 오류가 발생했습니다.');
            }
        });
    }
}
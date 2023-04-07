var sec_obj = [];

$(function(){
    pjsec.init();
    pjsec.getGroup(2,'','cate1');
});
 

var pjsec = {
    setobj : [],
    cate1 : {'key':0,'name':''},
    cate2 : {'key':0,'name':''},

    init : function(){
        this.setobj = [];
        
        $("#pj-sec-form").find("input[type='checkbox'][name='sec_service[]'],[name='sec_servicetype[]']").on('click',function(){
            var $this = $(this);
            var name = $this.attr("name");
            var value = $this.val();
            if( $this.prop("checked") == true ){
                pjsec.addForm(name,value);
            }else{
                pjsec.delForm(name,value);
            }
        });
    },

    addForm : function(key,value){
        var len = pjsec.setobj.length;
        var tmp_ = {key:key,value:value};
    
        pjsec.setobj[len] = tmp_;

        var tpl = "<li data=\""+key+"|"+value+"\">"+value+"<button class=\"btn_del\" onclick=\"pjsec.delForm('"+key+"','"+value+"')\">×</button></li>";
        $("#pj-sec-tag-lists").append(tpl);        
    },
    delForm : function (key,value){
        $("#pj-sec-tag-lists li[data='"+key+"|"+value+"']").remove();
        $("input[name='"+key+"'][value='"+value+"']").prop("checked",false);
        for( var k in pjsec.setobj ){
            if( pjsec.setobj[k].key == key && pjsec.setobj[k].value == value ){
                pjsec.setobj.splice(k,1);
                break;
            }
        }
    },

    getGroup : function(depth,pno){

        var $form = $("#pj-sec-form");
        var datas = {depth:depth,pno:pno};
        requestAjax.request('/info/group/list',datas,'GET','JSON',true,function(res){
            var $target = $("#wpl-sec-group");
            var litpl = "";
            switch( depth ){
                case 2 :
                    for( var k in res.data.glists ){
                        var data = res.data.glists[k];
                        litpl += "<li><a href=\"javascript:void(0);\" data=\""+data['name']+"\" uid=\""+data['uid']+"\">"+data['name']+"</a></li>";
                    }
                    $target.find('.tmenu').html(litpl);
                    $target.find('.tmenu li a').on('click',function(){
                        var uid = $(this).attr("uid");
                        var name = $(this).attr("data");
                        $form.find("input[name='cate1']").val(uid);
                        $form.find("input[name='cate1_nm']").val(name);
                        pjsec.getGroup(3,uid);
                    });
                    break;
                case 3 :
                    litpl += "<span><a href=\"javascript:void(0);\" data=\"\" uid=\"\">전체</a></span>";
                    for( var k in res.data.glists ){
                        var data = res.data.glists[k];
                        litpl += "<span><a href=\"javascript:void(0);\" data=\""+data['name']+"\" uid=\""+data['uid']+"\">"+data['name']+"</a></span>";
                    }
                    $target.find('.tcon li').html(litpl);
                    $target.find('.tcon a').on("click",function(){
                        var uid = $(this).attr("uid");
                        var name = $(this).attr("data");
                        $form.find("input[name='cate2']").val(uid);
                        $form.find("input[name='cate2_nm']").val(name);
                        if( !name ){
                            name = $form.find("input[name='cate1_nm']").val();
                        }
                        $(".seccate-selector").html(name);
                        $(".select_view-button").prop("checked",false);
                    });
                    break;
            }
        });
    },

    sec_submit : function(){        
        var $target = $("#pj-sec-form");
        $target.submit();
    }
}

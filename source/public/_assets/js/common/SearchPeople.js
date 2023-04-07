var SearchPeople = {
    search : function(type){
        if( type =='open' ){            
            var sec_key = "";
            var sec_val = "";
        }else{
            var $form = $("#search-people-form");
            var sec_key = $form.find("select[name='sec_key']").val();
            var sec_val = $form.find("input[name='sec_val']").val();
        }
        var datas = {sec_key:sec_key,sec_val:sec_val};
        requestAjax.request('/common/searchpeople',datas,'POST','JSON',true,function(res){
            getModal(res.data.template,'cmmodal-search-people');
            UserInfoPop();
        });
    }
}
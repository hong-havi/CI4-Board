var requestAjax = {
    loading_flag : true,

    request : function(url,datas,type,dataType,async, returnfunc){

        datas = ( datas ) ? datas : "";
        type = ( type ) ? type : "POST";
        dataType = ( dataType ) ? dataType : "JSON";
        async = ( async ) ? async : false;
        

        if( !url ){
            alert('잘못된 접근입니다.');
            return false;
        }
        

        if( type == 'FILE' ){
            type = "POST";
            var sendData = datas;
            var processData = false;
            var contentType = false;
        }else if( type == 'FORM' ){
            type = "POST";
            var sendData = datas;
            var processData = false;
            var contentType = false;
        }else{
            var sendData = this.dataMake(datas);
            var processData = true;
            var contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        }

        var result;
        $.ajax({
            url : url,
            data : sendData,
            dataType : dataType,
            processData: processData,
            contentType: contentType,
            type : type,
            async : async,
            beforeSend : function(){
                requestAjax.loading('load');
            },
            error : function(res){
                try{
                    if( !res.responseJSON ){
                        res.responseJSON = JSON.parse(res.responseText);
                    }
                    var message = ( isEmpty(res.responseJSON.messages.error) != true ) ? res.responseJSON.messages.error : null;
                    if(message){
                        alert(message);
                        return false;
                    }else{
                        alert('오류가 발생했습니다.\nMessage:Request Error 1');
                        return false;
                    }
                }catch(error){
                    alert('오류가 발생했습니다.\nMessage:Request Error 2');
                    return false;
                }
                
                return false;
            },
            
            success : function(ret) {
                result = ret;
                if( async == true){
                    returnfunc(result);
                    return false;
                }
                if( ret.status != 1 && dataType != 'HTML'){
                    alert(ret.message);
                }
            },
            complete : function(){
                requestAjax.loading('end');
            }
        });

        return result;
    },
    dataMake : function(datas){

        
        var def = {agent:$AGENT};
        
        if( typeof datas == 'object' ){
            var result = $.extend({},def,datas);
        }else{
            var result = def;
        }
        return result;
    },
    loading : function(action){
        if( this.loading_flag == false ) return true;

        switch(action){
            case 'load' :
                $("body").loading({
                    overlay : "",
                    message: '데이터 처리중입니다. 잠시 기다려주세요 ....',
                    theme: "dark"
                });
                break;
            case 'end' :
                $("body").loading('stop');
                break;
        }
    }
}
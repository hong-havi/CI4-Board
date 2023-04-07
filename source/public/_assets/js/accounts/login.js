function accounts(){
    var acc_id = $("input[name='acc_id']").val();
    var acc_pw = $("input[name='acc_pw']").val();
    var acc_cnumber = $("input[name='acc_cnumber']").val();
    acc_cnumber = (acc_cnumber) ? acc_cnumber : "";

    if( !acc_id ){
        //alert('아이디를 입력해 주세요.');
       // return false;
    }

    if( !acc_pw ){
       // alert('비밀번호를 입력해 주세요.');
      //  return false;
    }

    var datas = {
        acc_id:acc_id,
        acc_pw:acc_pw,
        acc_cnumber : acc_cnumber
    }
    requestAjax.request('/accounts/login',datas,'POST','JSON',true,function(res){
        if( res.status == 1 ){
          location.href='/';
        }else{
          alert(res.message);
        }
        
    });
}

function sendNumber(){
    var acc_id = $("input[name='acc_id']").val();
    var acc_pw = $("input[name='acc_pw']").val();

    if( !acc_id ){
        alert('아이디를 입력해 주세요.');
        return false;
    }

    if( !acc_pw ){
        alert('비밀번호를 입력해 주세요.');
        return false;
    }

    var datas = {
        acc_id:acc_id,
        acc_pw:acc_pw
    }
    requestAjax.request('/accounts/cnumber',datas,'POST','JSON',true,function(res){
        if( res.status == 1 ){
           alert("등록하신 핸드폰으로 인증번호가 발송되었습니다.");
        }else{
           alert(res.message);
        }        
    });
    
}
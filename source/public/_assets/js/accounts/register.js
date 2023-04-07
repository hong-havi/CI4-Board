$(function(){
    $(".form-date").mask('9999-99-99');
    $(".form-hphone").mask('999-9999-9999');
    $(".form-iphone").mask('99-9999-9999');
    
    $.validator.setDefaults( {
      submitHandler: function (e) {
        var datas = $("#accform").serializeObject();
        requestAjax.request('/accounts/register',datas,'POST','JSON',true,function(res){
          if( res.status == 1 ){
            alert(res.message);
            location.href='/accounts/login';
          }else{
            alert(res.message);
          }
        });
        return false;
      }
    });
    
    $('#accform').validate({
        rules : {
            uname : {
                required : true,
                normalizer: function( value ) {
                  return $.trim( value );
                }
            },
            birth : {
                required : true
            },
            sex : {
                required : true
            },
            userid: {
              required: true,
              minlength: 8,
              maxlength: 20,
              userid : true
            },
            upassword: {
              required: true,
              minlength: 8,
              maxlength: 20,
              passwords : true
            },
            upassword_confirm: {
              required: true,
              equalTo: '#upassword'
            },
        },
        messages: {
            uname: '이름 실명을 입력해 주세요',
            birth: '생년월일을 입력해 주세요',
            sex: '성별을 선택해 주세요',
            userid: {
                'required' : '아이디를 입력해 주세요',
                'minlength' : '8~20자의 영문(소문자)과 숫자만 사용할 수 있습니다.',
                'maxlength' : '8~20자의 영문(소문자)과 숫자만 사용할 수 있습니다.',
                'userid' : '영문(소문자)과 숫자만 사용할 수 있습니다.',
            },
            upassword: {
                'required' : '비밀번호를 입력해 주세요',
                'minlength' : '영문,숫자,특수문자(!@#$%^&*()_-)포함 8~20자만 사용할 수 있습니다.',
                'maxlength' : '영문,숫자,특수문자(!@#$%^&*()_-)포함 8~20자만 사용할 수 있습니다.',
                'passwords' : '영문,숫자,특수문자(!@#$%^&*()_-)포함해서 사용할 수 있습니다.'
            },
            upassword_confirm: {
                'required' : '비밀번호 확인을 입력해 주세요',
                'equalTo' : '입력된 비밀번호와 다릅니다.',
            },
        },
        errorElement: 'em',
        errorPlacement: function ( error, element ) {
          error.addClass( 'invalid-feedback' );
          if ( element.prop( 'type' ) === 'radio' ) {
            error.insertAfter( element.parent( 'label' ) );
          } else {
            error.insertAfter( element );
          }
        },
        highlight: function ( element, errorClass, validClass ) {
          $( element ).addClass( 'is-invalid' ).removeClass( 'is-valid' );
        },
        unhighlight: function (element, errorClass, validClass) {
          $( element ).addClass( 'is-valid' ).removeClass( 'is-invalid' );
        }
    });
});

$.validator.methods.userid = function( value, element ) {
    return this.optional( element ) || /^[a-z0-9+]*$/.test( value );
}

$.validator.methods.passwords = function( value, element ) {
    return this.optional( element ) || /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_-])[A-Za-z\d!@#$%^&*()_-].{8,20}$/.test( value );
}

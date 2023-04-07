var $AGENT = "PC";



function getModal( html , modal_class , target , option){

    var target = (target) ? target : "common_modal";
    var modal_class = (modal_class) ? modal_class : "";
    var html = (html) ? html : "";
    
    var def_option = {backdrop: 'static', keyboard: false, size:''};
    var option = (option) ? $.extend({},def_option,option) : def_option;
    
    var $target = $("#"+target);

    var class_list = $target[0].classList;
    class_len = class_list.length;
    for( var i = 0 ; i < class_len ; i++ ){
        var v = class_list[i];
        if( v.indexOf('cmmodal-') != -1){
            $target.removeClass(v);
            i++; 
        }
    }

    if(option.size){
        $target.find('.modal-dialog').addClass(option.size);
    }else{
        $target.find('.modal-dialog').removeClass('modal-lg');
    }

    $target.find(".modal-content").html(html);
    $target.addClass(modal_class);
    $target.modal(option);
    
}


function closeModal( target ){

    var target = (target) ? target : "common_modal";
    
    var $target = $("#"+target);
    $target.find('.modal-content').html('');
    $target.modal('hide');
    
}

function isEmpty(value){ 
    if( value == "" || value == null || value == undefined || ( value != null && typeof value == "object" && !Object.keys(value).length ) ){ 
        return true 
    }else{ 
        return false 
    } 
}

function extractLast( term ) {
	return split( term ).pop();
}

function split( val ) {
	return val.split( /,\s*/ );
}

function getfileSize(x) {
    var s = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'];
    var e = Math.floor(Math.log(x) / Math.log(1024));
    return (x / Math.pow(1024, e)).toFixed(2) + " " + s[e];
}


function UserInfoPop(){
    $(".userinfopop").popover({
        'html':true,
        'content': function(e){
            
            $('.userinfopop').popover('hide');
            var uno = $(this).attr("udata");
            var datas = {};
            requestAjax.loading_flag = false;
            var res = requestAjax.request('/common/profilepop/'+uno,datas,'GET','HTML');
            requestAjax.loading_flag = true;
            return res;
        }
    });
}

function copy_nowlink(trb) {
	var IE=(document.all)?true:false;
	if (IE) {
		alert("복사되었습니다. Ctrl+C를 눌러 클립보드로 복사하세요");
		window.clipboardData.setData("Text", trb);
	} else {
		trb = location.href;
		temp = prompt("이 글의 주소입니다. Ctrl+C를 눌러 클립보드로 복사하세요", trb);
	}
}

function copy_clipboard(trb) {
	var IE=(document.all)?true:false;
	if (IE) {
		alert("복사되었습니다. Ctrl+C를 눌러 클립보드로 복사하세요");
		window.clipboardData.setData("Text", trb);
	} else {
		temp = prompt("해당 내용을 복사하시겠습니까?. Ctrl+C를 눌러 클립보드로 복사하세요", trb);
	}
}

function datepickerset(target,selectfunc){

    $(target).datepicker({
        dateFormat:'yy-mm-dd',
   
        prevText:"<",
        nextText:">",
   
        monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
        monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
        dayNames: ['일','월','화','수','목','금','토'],
        dayNamesShort: ['일','월','화','수','목','금','토'],
   
        dayNamesMin: ['일','월','화','수','목','금','토'],
        showMonthAfterYear: true,
        yearSuffix: '년',
        onSelect: function(date){
            if(selectfunc){
                selectfunc(date);
            }
        }
    }).attr('readonly', 'readonly');
    $(target).click(function(){
        $("#ui-datepicker-div").css("position",'absolute');
    });
}


function enterinput(e,target){
    if( e.keyCode == 13 ){
        $(target).click();
    }
}
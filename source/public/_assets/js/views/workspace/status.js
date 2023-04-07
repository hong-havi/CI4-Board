$(function (){

//dashboard 전체 진행률 도넛 차트

	$('.chart_box .chart_comm').each(function(){
		var ctId= $(this).attr('id'),
			ctVal = $(this).next().find('.value'),
			ctColor = ctVal.css('color'),
			ctPer = parseInt(ctVal.html().replace('%',''));

		Nwagon.chart({
			'dataset': {
				values: [ctPer, 100-ctPer],
				colorset: [ctColor, '#e5e5e5'],
				fields: ['진행률', '전체']
			},
			'donut_width' : 30,
			'core_circle_radius':55.5,
			'chartDiv': ctId,
			'chartType': 'donut',
			'chartSize': {width:255, height:175}		
		});
	})


  });

var sec_obj = [];

$(function(){
    status_sec.init();
});
 

var status_sec = {
    setobj : [],
    cate1 : {'key':0,'name':''},
    cate2 : {'key':0,'name':''},

    init : function(){
        this.setobj = [];
        
        $("#status_form").find("[name='sec_service[]'],[name='sec_servicetype[]']").on('click',function(){
            var $this = $(this);
            var name = $this.attr("name");
            var value = $this.val();
            if( $this.prop("checked") == true ){
                status_sec.addForm(name,value);
            }else{
                status_sec.delForm(name,value);
            }
        });
    },

    addForm : function(key,value){
        var len = status_sec.setobj.length;
        var tmp_ = {key:key,value:value};
    
        status_sec.setobj[len] = tmp_;

        var tpl = "<li data=\""+key+"|"+value+"\">"+value+"<button class=\"btn_del\" onclick=\"status_sec.delForm('"+key+"','"+value+"')\">×</button></li>";
        $("#status-sec-tag-lists").append(tpl);        
    },
    delForm : function (key,value){
        $("#status-sec-tag-lists li[data='"+key+"|"+value+"']").remove();
        $("input[name='"+key+"'][value='"+value+"']").prop("checked",false);
        for( var k in status_sec.setobj ){
            if( status_sec.setobj[k].key == key && status_sec.setobj[k].value == value ){
                status_sec.setobj.splice(k,1);
                break;
            }
        }
    
    },
    sec_submit : function(){        
        var $target = $("#status_form");
        $target.submit();
    }
}

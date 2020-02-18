$(document).ready(function(){
	$("[data-event=click], [data-event=change]").each(function( index ) {

    var event = $(this).data("event");

    $(this).on(event,function(){

      var url = $(this).data("url");

      if(! typeof $(this).data("target") == 'undefined'){
        var target = ($(this).data("target") == '') ? '' : $(this).data("target");
      }else{
        var target = ($(this).data("target") == '') ? '' : $(this).data("target");
      }

      if(! typeof $(this).data("type") == 'undefined'){
        var type = ($(this).data("type") == '') ? 'GET' : $(this).data("type");
      }else{
        var type = ($(this).data("type") == '') ? 'GET' : $(this).data("type");
      }

      if(! typeof $(this).data("datatype") == 'undefined'){
        var datatype = ($(this).data("datatype") == '') ? 'json' : $(this).data("datatype");
      }else{
        var datatype = ($(this).data("datatype") == '') ? 'json' : $(this).data("datatype");
      }

      if(! typeof $(this).data("callback") == 'undefined'){
        var callback = ($(this).data("callback") == '') ? '' : $(this).data("callback");
      }else{
        var callback = ($(this).data("callback") == '') ? '' : $(this).data("callback");
      }

      if(! typeof $(this).data("datavalue") == 'undefined'){
        var datavalue = ($(this).data("datavalue") == '') ? '' : $(this).data("datavalue");
      }else{
        var datavalue = ($(this).data("datavalue") == '') ? '' : $(this).data("datavalue");
      }
      if(datavalue == 'self'){
        datavalue = $(this).attr("name")+"="+$(this).val();
      }

      $.ajax({
          type: type,
          url: url,
          data: datavalue,
          datatype: datatype,
          success: function(result) {
            if(callback == ''){
              if(target == ''){
                alert("Please set target or callback function.");
              }else{
                $(target).html(result);
                $(target).show();
              }
            }else{
              window[callback](result);
            }
          },
          error:function(result){
            alert("Something is wrong.");
          }
      });
    });
  });
});
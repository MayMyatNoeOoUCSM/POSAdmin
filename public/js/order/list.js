$(document).ready(function(){
	$('.fa-sort').on('click', function () { alert()
		  $('#sorting_column').val($(this).attr('id'))
		  if ($('#sorting_order').val() == 'asc') {
		    $('#sorting_order').val('desc')
		  } else {
		    $('#sorting_order').val('asc')
		  }
		  $('#frm_order').submit()
	});
	$(".custom_pg_size").on("change",function(){ //alert()
			$("#frm_order").submit();
	});
	$('input[type=checkbox]').on("click",function(){ 
		 $('input[type="checkbox"].' + $(this).attr('class'))
	    .not(this)
	    .prop('checked', false)
		$('#frm_order').submit();
	});

	$("#btn_reset").on('click',function(){
		window.location = "order";
	});
});

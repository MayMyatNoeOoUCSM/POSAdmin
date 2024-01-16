$('.btn_change_qty').on('click',function(){
  if (!$(".min_qty_"+$(this).attr('data-id')).val()) {
    $("#required_min_qty_"+$(this).attr('data-id')).css('display', 'block');
    $("#required_min_qty_"+$(this).attr('data-id')).text('minimum quantity is required.')
  } else if (!$.isNumeric($(".min_qty_"+$(this).attr('data-id')).val())) {
  	 $("#required_min_qty_"+$(this).attr('data-id')).css('display', 'block');
  	 $("#required_min_qty_"+$(this).attr('data-id')).text('minimum quantity must be number.')
  } else {
    $("#change_min_qty_form_"+$(this).attr('data-id')).submit();
  }
});

$('.btn_change_price').on('click',function(){
  if (!$(".price_"+$(this).attr('data-id')).val()) {
    $("#required_price_"+$(this).attr('data-id')).css('display', 'block');
    $("#required_price_"+$(this).attr('data-id')).text('price is required.')
  } else if (!$.isNumeric($(".price_"+$(this).attr('data-id')).val())) {
  	 $("#required_price_"+$(this).attr('data-id')).css('display', 'block');
  	 $("#required_price_"+$(this).attr('data-id')).text('price must be number.')
  } else {
    $("#change_price_form_"+$(this).attr('data-id')).submit();
  }
});

$(".fa-sort").on("click",function(){
	$("#sorting_column").val($(this).attr("id"));
	if($("#sorting_order").val()=="asc"){
		$("#sorting_order").val("desc");
	}else{
		$("#sorting_order").val("asc");
	}
	$("#custom_pg_size").val($('.custom_pg_size').val())
	$("#frm_product").submit();
});
$(".custom_pg_size").on("change",function(){
	    $("#custom_pg_size").val($(this).val())
		$("#frm_product").submit();
});

$(".import_excel").on('click',function(){
	$("#importFile").click();
});
$('#importFile').change(function() { //alert(importurl)
	$('#frm_product').attr('action', importurl);
	document.getElementById("frm_product").method = "post";
	$("#frm_product").submit();
});
$(document).ready(function(){
	$(".custom_pg_size").on("change",function(){
		$("#custom_pg_size").val($(this).val());
		$("#frm_category").submit();
	});
	$("#btn_search").on("click",function(){
		$("#custom_pg_size").val($('.custom_pg_size').val());
		$("#frm_category").submit();
	});
})
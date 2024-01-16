$("#select_report").on("change",function(){
	$('.report_filters').hide()
	if($(this).val()=='best_selling_report') { 
		$("#best_selling_report_filters").show();
	}
	if($(this).val()=='sales_by_product_category_report') { 
		$("#sales_by_product_category_report_filters").show();
	}
	if($(this).val()=='damage_and_loss_report') { 
		$("#damage_and_loss_report_filters").show();
	}
	$('.table ').html('');
	$('.pagination ').html('');

})

$(".custom_pg_size").on("change",function(){
		$("#frm_report").submit();
});


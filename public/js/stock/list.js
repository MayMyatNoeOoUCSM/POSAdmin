$(document).ready(function(){
	$(".custom_pg_size").on("change",function(){
		$("#frm_stock").submit();
	})

	// $('input[name="order_by_qty"]').on("click",function(){
	// 	$("#frm_stock").submit();
	// });
	$('.form-check-input').on("click",function(){
		$("#frm_stock").submit();
	});

	$('button[type="reset"]').click(function(){
        window.location ="stock";
  	});
})

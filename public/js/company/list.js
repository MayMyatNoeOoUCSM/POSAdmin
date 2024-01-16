$(document).ready(function(){
	$(".custom_pg_size").on("change",function(){ //alert()
			$("#frm_company").submit();
	});
	$('.deleteModal').on('click',function(){
      let id = $(this).attr('data-id');
       $('#deleteForm').attr('action', 'company/'+id);
    })
});
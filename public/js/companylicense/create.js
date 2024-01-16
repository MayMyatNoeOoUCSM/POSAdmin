$(".contact_check").on("click",function(){
  $('.contact_check').not(this).prop('checked', false);
  if($(this).val() == 1){
    $("#contact_person_name").prop("disabled",true);
    $("#contact_person_phone").prop("disabled", true);
  }else{
    $("#contact_person_name").prop("disabled",false);
    $("#contact_person_phone").prop("disabled", false);
  }
})
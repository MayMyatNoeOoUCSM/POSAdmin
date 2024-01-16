function showImage(src, target) {
  var fr = new FileReader();
  fr.onload = function () {
    target.src = fr.result;
  }
  document.getElementById("target").style.display = "block";
  document.getElementById("image").style.display = "block";
  fr.readAsDataURL(src.files[0]);
  if (src.length === 0) {
    document.getElementById("target").style.display = "none";
  }
}
function putImage() {
  var src = document.getElementById("image");
  src = document.getElementById("image");
  var target = document.getElementById("target");
  showImage(src, target);
}

$("#edit_product_category_id").on("change",function(){
  alert("Are you sure to change product category?");
});

$(".barcode_check").on("click",function(){
  $('.barcode_check').not(this).prop('checked', false);
  if($(this).val() == 1){
    $("#barcode").prop("disabled",true)
  }else{
    $("#barcode").prop("disabled",false)
  }
})
$(document).ready(function () {
  // if (staff['warehouse_id']) {
  //   document.getElementById("shop_label").style.display = "none";
  //   document.getElementById("shop_id").style.display = "none";
  //   document.getElementById("warehouse_label").style.display = "block";
  //   document.getElementById("warehouse_id").style.display = "block";
  // } else 
    var role = document.getElementById("role").value;
    if(role==staff['role'] && staff['role'] ==2 ) { 
      document.getElementById("shop_label").style.display = "none";
      document.getElementById("shop_id").style.display = "none";
      document.getElementById("company_label").style.display = "block";
      document.getElementById("company_id").style.display = "block";
    }else if(role==staff['role'] && staff['role'] ==3 || staff['role'] ==4 || staff['role']==5 || staff['role'] ==6 || staff['role'] ==7){
      document.getElementById("company_label").style.display = "none";
      document.getElementById("company_id").style.display = "none";
      document.getElementById("shop_label").style.display = "block";
      document.getElementById("shop_id").style.display = "block";
    }
  
});
function getStaffNo() {
  var role = document.getElementById("role").value;
  if (role == "") {
    document.getElementById("staff_number").value = "";
  } else if(role == staff['role']) {
    document.getElementById("staff_number").value = staff['staff_number'];
  } else {
    document.getElementById("staff_number").value = staff_no_all_role[role - 1];
  }
  console.log(role);
  if (role == company_admin) {
    document.getElementById("shop_label").style.display = "none";
    document.getElementById("shop_id").style.display = "none";
    document.getElementById("company_label").style.display = "block";
    document.getElementById("company_id").style.display = "block";
  } else if (role == shop_admin || role == cashier_staff || role== kitchen_staff || role==waiter_staff || role==sale_staff) {
    document.getElementById("company_label").style.display = "none";
    document.getElementById("company_id").style.display = "none";
    document.getElementById("shop_label").style.display = "block";
    document.getElementById("shop_id").style.display = "block";
  } else {
    document.getElementById("shop_label").style.display = "none";
    document.getElementById("shop_id").style.display = "none";
    document.getElementById("company_label").style.display = "none";
    document.getElementById("company_id").style.display = "none";
  }
}
function showImage(src, target) {
  var fr = new FileReader();
  fr.onload = function () {
    target.src = fr.result;
  }
  document.getElementById("target").style.display = "block";
  document.getElementById("image").style.display = "none";
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
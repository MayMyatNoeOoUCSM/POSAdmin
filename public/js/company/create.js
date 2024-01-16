function showImage(src, target) {
  var fr = new FileReader();
  fr.onload = function () {
    target.src = fr.result;
  }
  document.getElementById("target").style.display = "block";
  document.getElementById("profile").style.display = "block";
  fr.readAsDataURL(src.files[0]);
  if (src.length === 0) {
    document.getElementById("target").style.display = "none";
  }
}
function putImage() {
  var src = document.getElementById("profile");
  src = document.getElementById("profile");
  var target = document.getElementById("target");
  showImage(src, target);
}
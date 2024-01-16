function getSubCategory() {
  var request_data = {
    productCategory: $("#product_category").val(),
  };
  $.ajax({
    url: 'get_sub_category',
    data: request_data,
    type: "GET",
    success: function (data) {
      let productSubCategoryList = data['productSubCategoryList'];
      let productList = data['productList'];
      $('#product_sub_category').find('option').remove().end().append(' <option value="" selected>Select Sub Category</option>');
      for (var i = 0; i < productSubCategoryList.length; i++) {
        $('#product_sub_category').append('<option value="' + productSubCategoryList[i].id + '">' + productSubCategoryList[i].name + '</option>');
      }
      $('#selectProduct').find('option').remove().end().append(' <option value="" selected>Choose Product</option>');
      for (var i = 0; i < productList.length; i++) {
        $('#selectProduct').append('<option value="' + productList[i].id + '">' + productList[i].name + '</option>');
      }
    },
    error: function (xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      $(".error_status").text(err);
      $(".error").css('display', 'block');
    },
  });
}
function getProduct() {
  var request_data = {
    productSubCategory: $("#product_sub_category").val(),
  };
  $.ajax({
    url: 'get_product',
    data: request_data,
    type: "GET",
    success: function (data) {
      let productList = data['productList'];
      $('#selectProduct').find('option').remove().end().append(' <option value="" selected>Choose Product</option>');
      for (var i = 0; i < productList.length; i++) {
        $('#selectProduct').append('<option value="' + productList[i].id + '">' + productList[i].name + '</option>');
      }
    },
    error: function (xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      $(".error_status").text(err);
      $(".error").css('display', 'block');
    },
  });
}
$("#search").click(function () {
  var request_data = {
    productId: $("#selectProduct").val(),
    productCategory: $("#product_category").val(),
    productSubCategory: $("#product_sub_category").val(),
  };
  $.ajax({
    url: 'search_product',
    data: request_data,
    type: "GET",
    success: function (data) {
      let warehouseList = data['warhouseList'];
      let shopList = data['shopList'];
      let productList = data['productList'];
      localStorage.setItem('product_list_length', productList.length);
      $("#productDetailTable > tr").remove();
      if(productList.length > 0){
        $('#btn_store').prop('disabled', false);
      }else{
        $('#btn_store').prop('disabled', true);
      }
      for (var i = 0; i < productList.length; i++) {

        $('#productDetailTable').append(
          '<tr>' +
          '<td> <input type="hidden" class="product_id" name="product_id[]" value=' + productList[i].id + '>' + productList[i].name + '</td>' +
          '<td><input type="checkbox" id="chk_option0'+i+'" value="0' + i + '" name="w_s" class="check_status"> &nbsp; Warehouse <br> ' +
          '<input type="checkbox" id="chk_option1'+i+'" value="1' + i + '" name="w_s" class="check_status"> &nbsp; Shop </td > ' +
          '<td><select name="warehouse_id[]" id="selectWarehouse' + i + '" class="form-control" style="display:none;"> ' +
          '<option value="">Choose Warehouse</option></select > ' +
          '<select name="shop_id[]" id="selectShop' + i + '" class="form-control" style="display:none;"> ' +
          '<option value="">Choose Shop</option></select ></td > ' +
          '<td><input type="text" class="form-control right qty' + i + '" name="qty[]"></td>' +
          '<td><input type="text" class="form-control right min_qty' + i + '"" name="min_qty[]" value=' + productList[i].minimum_quantity + '></td>' +
          '<td><input type="text" class="form-control right price' + i + '"" name="price[]"></td>' +
          '<td><input type="text" class="form-control" name="remark[]"></td>' +
          '<td> <button class="btn btn-danger" type="button" onclick="rowDelete(this);"><span class="nav-icon fas fa-trash"></span></button></td>' +
          '</tr>'
        );
        warehouseList.forEach(function (warehouse) {
          $('#selectWarehouse' + i).append('<option value="' + warehouse.id + '">' + warehouse.name + '</option>');
        });
        shopList.forEach(function (shop) {
          $('#selectShop' + i).append('<option value="' + shop.id + '">' + shop.name + '</option>');
        });
      };
    },
    error: function (xhr, status, error) {
      var err = eval("(" + xhr.responseText + ")");
      $(".error_status").text(err);
      $(".error").css('display', 'block');
    },
  });
});

function rowDelete(btndel) {
  var rowCount = $("#productDetailTable td").closest("tr").length;
  if (typeof (btndel) == "object") {
    var response = confirm("Are you sure you want to delete this row?");
    if (response == true) {
      $(btndel).closest('tr').remove();
    } else {
      return false;
    }
  } else {
    return false;
  }
}
$('body').on('change', 'input[name="w_s"]', function () {
  let radioId = this.value[this.value.length - 1];
  if (this.value == '0' + radioId) {
    $("#selectShop" + radioId).css('display', 'none');
    $("#selectWarehouse" + radioId).css('display', 'block');
    $("#selectWarehouse" + radioId).css('width', '200');
    $("#selectWarehouse" + radioId).css('margin-left', 'auto');
    $("#selectWarehouse" + radioId).css('margin-right', 'auto');

    $("#chk_option1"+radioId).prop("checked", false);
    $("#selectShop" + radioId).val($("#selectShop" + radioId+ "option:first").val());
   
  } else if (this.value == '1' + radioId) {
    $("#selectWarehouse" + radioId).css('display', 'none');
    $("#selectShop" + radioId).css('display', 'block');
    $("#selectShop" + radioId).css('width', '150');
    $("#selectShop" + radioId).css('margin-left', 'auto');
    $("#selectShop" + radioId).css('margin-right', 'auto');

    $("#chk_option0"+radioId).prop("checked", false);
    $("#selectWarehouse" + radioId).val($("#selectWarehouse" + radioId+ "option:first").val());
    
  }
});
function isInteger(value) {
  return /^\d+$/.test(value);
}
function isFloat(value) {
  var float = /^\d+\.\d+$/.test(value);
  var int = /^\d+$/.test(value);
  if (float || int) {
    return true;
  }
}
$("#btn_store").click(function () {
  var submitOk = true;
  var rowCount = localStorage.getItem('product_list_length');
  $('.req').html('');
  var row = 2;
  for (var i = 0; i < rowCount; i++) {
    if ($('.qty' + i).val() === undefined) {
      continue;
    }
    if (!($('#selectWarehouse' + i).val() || $('#selectShop' + i).val())) {
      $("#productDetailTable td").closest('tr:nth-child(' + (row) + ')').children('td:nth-child(3)').append('<p class="text-danger req">Required</p>');
      submitOk = false;
    }
    if (!$('.qty' + i).val())
    {
      $("#productDetailTable td").closest('tr:nth-child(' +  (row) + ')').children('td:nth-child(4)').append('<p class="text-danger req">Required</p>');
      submitOk = false;
    } else if (!isInteger($('.qty' + i).val())){
      $("#productDetailTable td").closest('tr:nth-child(' +  (row) + ')').children('td:nth-child(4)').append('<p class="text-danger req">Numeric</p>');
      submitOk = false;
    }
    if (!$('.min_qty' + i).val()) {
      $("#productDetailTable td").closest('tr:nth-child(' +  (row) + ')').children('td:nth-child(5)').append('<p class="text-danger req">Required</p>');
      submitOk = false;
    } else if (!isInteger($('.min_qty' + i).val())) {
      $("#productDetailTable td").closest('tr:nth-child(' +  (row) + ')').children('td:nth-child(5)').append('<p class="text-danger req">Numeric</p>');
      submitOk = false;
    }
    if (!$('.price' + i).val()) {
      $("#productDetailTable td").closest('tr:nth-child(' +  (row) + ')').children('td:nth-child(6)').append('<p class="text-danger req">Required</p>');
      submitOk = false;
    } else if (!isFloat($('.price' + i).val())) {
      $("#productDetailTable td").closest('tr:nth-child(' +  (row) + ')').children('td:nth-child(6)').append('<p class="text-danger req">Double</p>');
      submitOk = false;
    }
    row++;
  }
  if (submitOk) {
    $('#stock_store_form').submit();
  }
});
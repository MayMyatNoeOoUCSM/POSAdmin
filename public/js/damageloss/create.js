$('#productDetailTable').on('keyup paste', '.number_only', function () {
  this.value = this.value.replace(/[^0-9]/g, '')
})

function getSubCategory() {
  var request_data = {
    productCategory: $('#product_category').val(),
  }
  $.ajax({
    url: '../stock/get_sub_category',
    data: request_data,
    type: 'GET',
    success: function (data) {
      let productSubCategoryList = data['productSubCategoryList']
      let productList = data['productList']
      $('#product_sub_category')
        .find('option')
        .remove()
        .end()
        .append(' <option value="" selected>Select Sub Category</option>')
      for (var i = 0; i < productSubCategoryList.length; i++) {
        $('#product_sub_category').append(
          '<option value="' +
            productSubCategoryList[i].id +
            '">' +
            productSubCategoryList[i].name +
            '</option>'
        )
      }
      $('#selectProduct')
        .find('option')
        .remove()
        .end()
        .append(' <option value="" selected>Choose Product</option>')
      for (var i = 0; i < productList.length; i++) {
        $('#selectProduct').append(
          '<option value="' +
            productList[i].id +
            '">' +
            productList[i].name +
            '</option>'
        )
      }
    },
    error: function (xhr, status, error) {
      var err = eval('(' + xhr.responseText + ')')
      $('.error_status').text(err)
      $('.error').css('display', 'block')
    },
  })
}
function getProduct() {
  var request_data = {
    productSubCategory: $('#product_sub_category').val(),
  }
  $.ajax({
    url: '../stock/get_product',
    data: request_data,
    type: 'GET',
    success: function (data) {
      let productList = data['productList']
      $('#selectProduct')
        .find('option')
        .remove()
        .end()
        .append(' <option value="" selected>Choose Product</option>')
      for (var i = 0; i < productList.length; i++) {
        $('#selectProduct').append(
          '<option value="' +
            productList[i].id +
            '">' +
            productList[i].name +
            '</option>'
        )
      }
    },
    error: function (xhr, status, error) {
      var err = eval('(' + xhr.responseText + ')')
      $('.error_status').text(err)
      $('.error').css('display', 'block')
    },
  })
}
$('#search').click(function () {
  if ($('#selectWarehouse').val() == '' && $('#selectShop').val() == '') {
    $('#warhouse_shop_required').text('Please choose warehouse or shop')
    return false
  }
  $('#productDetailTable tbody tr').remove();
  if($('#selectWarehouse').val() =="" && $('#selectShop').val() ==""){
    $("#warhouse_shop_required").text('Please choose warehouse or shop');
    return false;
  } else{
    $("#warhouse_shop_required").text('');
  }
  var request_data = {
    productId: $('#selectProduct').val(),
    productCategory: $('#product_category').val(),
    productSubCategory: $('#product_sub_category').val(),
    warehouse_id: $('#selectWarehouse').val(),
    shop_id: $('#selectShop').val(),
    damage_loss_search: 1,
  }
  $.ajax({
    url: '../stock/search_product',
    data: request_data,
    type: 'GET',
    success: function (data) {
      // let warehouseList = data['warhouseList']
      // let shopList = data['shopList']
      let productList = data['productList']
      localStorage.setItem('product_list_length', productList.length)
      $('#productDetailTable > tr').remove()
      for (var i = 0; i < productList.length; i++) {
        $('#productDetailTable tbody').append(
          '<tr>' +
            '<td> <input type="hidden" class="product_id" name="product_id[]" value=' +
            productList[i].id +
            '>' +
            productList[i].name +
            '</td>' +
            // '<td><select name="warehouse_id[]" id="selectWarehouse' + i + '" class="form-control" style="display:none;"> ' +
            // '<option value="">Choose Warehouse</option></select > ' +
            // '<select name="shop_id[]" id="selectShop' + i + '" class="form-control" style="display:none;"> ' +
            // '<option value="">Choose Shop</option></select ></td > ' +
            '<td><input type="checkbox" class="radioPrimary' +
            i +
            '" value="1" name="product_status[]"> &nbsp; Damage <br> ' +
            '<input type="checkbox" class="radioPrimary' +
            i +
            '" value="2" name="product_status[]"> &nbsp; Loss </td > ' +
            '<td class="position-relative"><input type="text" class="form-control number_only right qty' +
            i +
            '" name="qty[]"></td>' +
            //'<td><input type="text" class="form-control right min_qty' + i + '"" name="min_qty[]" value=' + productList[i].minimum_quantity + '></td>' +
            '<td><input type="text" class="form-control number_only right price' +
            i +
            '"" name="price[]" value="' +
            productList[i].sale_price +
            '" readonly></td>' +
            '<td><input type="text" class="form-control" name="remark[]"></td>' +
            '<td> <button data-toggle="modal" class="deleteModal iconButton" data-target="#deleteModalCenter" type="button"><span style="color:red" class="nav-icon fas fa-trash-alt iconSize"></span></button></td>' +
            '</tr>'
        );
      }
      if(productList.length >= 1)
      { $('#btn_store').prop('disabled', false)}
      else{
       $('#btn_store').prop('disabled', true)
      }
    },
    error: function (xhr, status, error) {
      var err = eval('(' + xhr.responseText + ')')
      $('.error_status').text(err)
      $('.error').css('display', 'block')
    }
  });
});
var object;
$('tbody').on('click','.deleteModal',function(){
    object = $(this);
});
function rowDelete() {
  $('#deleteModalCenter').modal('hide');
  $(object).closest('tr').remove();
  var rowCount = $('#productDetailTable td').closest('tr').length;
  if(rowCount >= 1) { 
    $('#btn_store').prop('disabled', false)}
  else {
    $('#btn_store').prop('disabled', true)
  }
  // var rowCount = $('#productDetailTable td').closest('tr').length
  // if (typeof btndel == 'object') {
  //   var response = confirm('Are you sure you want to delete this row?')
  //   if (response == true) {
  //     $(btndel).closest('tr').remove()
  //   } else {
  //     return false
  //   }
  // } else {
  //   return false
  // }
}

function isInteger(value) {
  return /^\d+$/.test(value)
}
function isFloat(value) {
  var float = /^\d+\.\d+$/.test(value)
  var int = /^\d+$/.test(value)
  if (float || int) {
    return true
  }
}
$('#btn_store').click(function () {
  var submitOk = true
  var rowCount = localStorage.getItem('product_list_length')
  $('.req').html('')
  var row = 1
  for (var i = 0; i < rowCount; i++) {
    if ($('.qty' + i).val() === undefined) {
      continue
    }

    if (!$('.qty' + i).val()) {
      $('#productDetailTable tbody td')
        .closest('tr:nth-child(' + row + ')')
        .children('td:nth-child(3)')
        .append('<p class="text-danger req position-absolute" style="display:contents;">Please fill.</p>')
      submitOk = false
    } else if (!isInteger($('.qty' + i).val())) {
      $('#productDetailTable td')
        .closest('tr:nth-child(' + row + ')')
        .children('td:nth-child(3)')
        .append('<p class="text-danger req position-absolute" style="display:contents;">Numeric</p>')
      submitOk = false
    }

    console.log( $('.radioPrimary'+i+':checked').val());
    if($('.radioPrimary'+i+':checked').val()==undefined){
       $('#productDetailTable tbody td' )
        .closest('tr:nth-child(' + row + ')')
        .children('td:nth-child(2)')
        .append('<p class="text-danger req">Please choose.</p>')
      submitOk = false
    }
    
    console.log($('.price' + i).val());
    if (!$('.price' + i).val()) {
      $('#productDetailTable tbody td')
        .closest('tr:nth-child(' + row + ')')
        .children('td:nth-child(4)')
        .append('<p class="text-danger req" style="display:contents;">Please fill.</p>')
      submitOk = false
    } else if (!isFloat($('.price' + i).val())) {
      $('#productDetailTable td')
        .closest('tr:nth-child(' + row + ')')
        .children('td:nth-child(4)')
        .append('<p class="text-danger req" style="display:contents;">Double</p>')
      submitOk = false
    }

    row++
  }
  if ($('#selectWarehouse').val() == '' && $('#selectShop').val() == '') {
    alert('Please select warehouse or shop')
    submitOk = false
  }
  if (submitOk) {
    if ($('#productDetailTable tr').length > 0) {
      $('#damage_loss_store_form').submit()
    }
  }
})

$(document).on('click', 'input[type="checkbox"]', function () {
  $('input[type="checkbox"].' + $(this).attr('class'))
    .not(this)
    .prop('checked', false)
})

$('#selectShop').on('change', function (e, isTriggered) {
  if (isTriggered === undefined) {
    $('#selectWarehouse').select2().val(null).trigger('change', [true])
  }
})
$('#selectWarehouse').on('change', function (e, isTriggered) {
  if (isTriggered === undefined) {
    $('#selectShop').select2().val(null).trigger('change', [true])
  }
})

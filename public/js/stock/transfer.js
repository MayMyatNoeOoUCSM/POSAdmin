function warehouseChange() {
  let warehouseId = document.getElementById("selectWarehouse").value;
  for (var i = 0; i < warehouseStockList.length; i++) {
    if (warehouseStockList[i].id == warehouseId) {
      document.getElementById("old_qty").value = warehouseStockList[i].quantity;
      document.getElementById("qty").value = warehouseStockList[i].quantity;
      document.getElementById("price").value = warehouseStockList[i].price;
      break;
    } else {
      document.getElementById("old_qty").value = '' ;
      document.getElementById("qty").value = '' ;
      document.getElementById("price").value = '' ;
    }
  }
}
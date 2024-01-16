
<table>
<thead>
  <tr>
      <th >Invoice Number</th>
      <th >Sale Date From</th>
      <th >Staff Name</th>
      <th >Terminal Name</th>
      <th >Amount</th>
      <th >Total</th>
      <th >Remark</th>
    
  </tr>
</thead>
<tbody>
  <tr>
      <td>{{$sale->invoice_number}}</td>
      <td>{{$sale->sale_date}}</td>
      <td>{{$sale->staff_name}}</td>
      <td>{{$sale->terminal_number}}</td>
      <td>{{number_format($sale->amount,2)}}</td>
      <td>{{number_format($sale->total,2)}}</td>
      <td>{{$sale->reason}}</td>
  </tr>
</tbody>
</table>
<table>
<thead>
  <tr>
      <th>No</th>
      <th>Product Name</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Remark</th>
  </tr>
</thead>
<tbody>
  <?php $number = 1;?>
  @foreach($saleDetailsList as $detailInfo)
      <tr>
        <td>{{ $number }}</td>
        <td>{{ $detailInfo->product_name}}</td>
        <td>{{ $detailInfo->price}}</td>
        <td>{{ $detailInfo->quantity}}</td>
        <td>{{$detailInfo->remark}}</td>
      </tr>
      <?php $number++;?>
  @endforeach
</tbody>
</table>
     

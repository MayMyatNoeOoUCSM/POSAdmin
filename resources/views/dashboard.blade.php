@extends('layouts.main')

@section('content-header')
    {{ __('Dashboard') }}
@endsection

@section('main-content')

@if($checkShopType == NULL)
<div class="row">
  {{-- today sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($totalCompany) ? number_format($totalCompany,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __("TOTAL COMPANIES")}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-layer-group"></i>
      </div>
    </div>
  </div>

  {{-- month's sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($totalActiveCompany) ? number_format($totalActiveCompany,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('TOTAL ACTIVE COMPANIES')}}</p>
      </div>
      <div class="icon">
        <i class="far fa-calendar-check"></i>
      </div>
    </div>
  </div>

  {{-- year's sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($totalExpireCompany) ? number_format($totalExpireCompany,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('TOTAL EXPIRE COMPANIES')}}</p>
      </div>
      <div class="icon">
        <i class="far fa-calendar-times"></i>
      </div>
    </div>
  </div>

  {{-- total sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
         <h3>{{$totalUsers}}</h3>
          <p class="innerBoxMargin">{{ __('TOTAL USERS')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-users"></i>
      </div>
    </div>
  </div>
</div>
@endif
@if($checkShopType == 'retails')
<div class="row">
  {{-- today sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($todaySaleTotal->sum) ? number_format($todaySaleTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __("TODAY'S SALES")}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-cart-arrow-down"></i>
      </div>
    </div>
  </div>

  {{-- month's sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($monthSaleTotal->sum) ? number_format($monthSaleTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('MONTH\'S SALES')}}</p>
      </div>
      <div class="icon">
        <i class="far fa-calendar-alt"></i>
      </div>
    </div>
  </div>

  {{-- year's sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($yearSaleTotal->sum) ? number_format($yearSaleTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('YEAR\'S SALES')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-sort-amount-up"></i>
      </div>
    </div>
  </div>

  {{-- total sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
         <h3>{{number_format($saleTotal->sum,2)}}</h3>
          <p class="innerBoxMargin">{{ __('TOTAL\'S SALES')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-calculator"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header border-0 card-header-paddingLeft">
        {{-- title --}}
        <div class="d-flex justify-content-between">
          <h3 class="card-title">{{ __('Weekly Sale Report')}}</h3>
        </div>
      </div>
      <div class="card-body card-body-padding">
        <div class="d-flex">
          <p class="d-flex flex-column">
            <span class="text-bold text-lg">${{ json_encode($weeklyreport['currentWeeklyDataTotal'])}}</span>
            <span>Sales Over Time</span>
          </p>
          <p class="ml-auto d-flex flex-column text-right">
            <span class="text-success alignLeft">
              <i class="fas {{ $weeklyreport['weeklyProgress']=='up'? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{number_format((float)$weeklyreport['weeklyProgressPercentage'], 2, '.', '')}}%
            </span>
            <span class="text-muted">Since last week</span>
          </p>
        </div>
        <div class="position-relative mb-4">
          <div class="chartjs-size-monitor">
            <div class="chartjs-size-monitor-expand">
              <div class=""></div>
            </div>
            <div class="chartjs-size-monitor-shrink">
              <div class=""></div>
            </div>
          </div>
          <canvas id="visitors-chart" height="200" width="487" class="chartjs-render-monitor" style="display: block; width: 487px; height: 300px;"></canvas>
        </div>
        <div class="d-flex flex-row justify-content-end">
          <span class="mr-2 paddingRight">
            <i class="fas fa-square text-primary"></i> This Week
          </span>
          <span>
            <i class="fas fa-square text-gray"></i> Last Week
          </span>
        </div>
      </div>
    </div>
  </div>
    <!-- /.col-md-6 -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header border-0 card-header-paddingLeft">
        <div class="d-flex justify-content-between">
          <h3 class="card-title">{{ __('Monthly Sale Report')}}</h3>
        </div>
      </div>
      <div class="card-body card-body-padding">
        <div class="d-flex">
          <p class="d-flex flex-column">
            <span class="text-bold text-lg">${{ json_encode($monthlyreport['currentYearlyDataTotal'])}}</span>
            <span>Sales Over Time</span>
          </p>
          <p class="ml-auto d-flex flex-column text-right">
            <span class="text-success alignLeft">
               <i class="fas {{ $monthlyreport['YearlyProgress']=='up'? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{number_format((float)$monthlyreport['YearlyProgressPercentage'], 2, '.', '')}}%
            </span>
            <span class="text-muted">Since last month</span>
          </p>
        </div>
        <div class="position-relative mb-4">
          <div class="chartjs-size-monitor">
            <div class="chartjs-size-monitor-expand">
              <div class=""></div>
            </div>
            <div class="chartjs-size-monitor-shrink">
              <div class=""></div>
            </div>
          </div>
          <canvas id="sales-chart" height="200" style="display: block; width: 487px; height: 300px;" width="487" class="chartjs-render-monitor"></canvas>
        </div>
        <div class="d-flex flex-row justify-content-end">
          <span class="mr-2 paddingRight">
            <i class="fas fa-square text-primary"></i> This year
          </span>
          <span>
            <i class="fas fa-square text-gray"></i> Last year
          </span>
        </div>
      </div>
    </div>
  </div>
    <!-- /.col-md-6 -->
</div>
@endif
@if($checkShopType == 'restaurant')
<div class="row">
  {{-- today order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($todayOrderTotal->sum) ? number_format($todayOrderTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __("TODAY'S ORDER")}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-bag"></i>
      </div>
    </div>
  </div>

  {{-- month's order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($monthOrderTotal->sum) ? number_format($monthOrderTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('MONTH\'S ORDER')}}</p>
      </div>
      <div class="icon">
        <i class="far fa-calendar-alt"></i>
      </div>
    </div>
  </div>

  {{-- year's order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($yearOrderTotal->sum) ? number_format($yearOrderTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('YEAR\'S ORDER')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-clipboard-list"></i>
      </div>
    </div>
  </div>

  {{-- total order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
         <h3>{{number_format($orderTotal->sum,2)}}</h3>
          <p class="innerBoxMargin">{{ __('TOTAL\'S ORDER')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-chart-line"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header border-0 card-header-paddingLeft">
        {{-- title --}}
        <div class="d-flex justify-content-between">
          <h3 class="card-title">{{ __('Weekly Order Report')}}</h3>
        </div>
      </div>
      <div class="card-body card-body-padding">
        <div class="d-flex">
          <p class="d-flex flex-column">
            <span class="text-bold text-lg">${{ json_encode($weeklyreport['currentWeeklyDataTotal'])}}</span>
            <span>Order Over Time</span>
          </p>
          <p class="ml-auto d-flex flex-column text-right">
            <span class="text-success alignLeft">
              <i class="fas {{ $weeklyreport['weeklyProgress']=='up'? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{number_format((float)$weeklyreport['weeklyProgressPercentage'], 2, '.', '')}}%
            </span>
            <span class="text-muted">Since last week</span>
          </p>
        </div>
        <div class="position-relative mb-4">
          <div class="chartjs-size-monitor">
            <div class="chartjs-size-monitor-expand">
              <div class=""></div>
            </div>
            <div class="chartjs-size-monitor-shrink">
              <div class=""></div>
            </div>
          </div>
          <canvas id="visitors-chart" height="200" width="487" class="chartjs-render-monitor" style="display: block; width: 487px; height: 300px;"></canvas>
        </div>
        <div class="d-flex flex-row justify-content-end">
          <span class="mr-2 paddingRight">
            <i class="fas fa-square text-primary"></i> This Week
          </span>
          <span>
            <i class="fas fa-square text-gray"></i> Last Week
          </span>
        </div>
      </div>
    </div>
  </div>
    <!-- /.col-md-6 -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header border-0 card-header-paddingLeft">
        <div class="d-flex justify-content-between">
          <h3 class="card-title">{{ __('Monthly Order Report')}}</h3>
        </div>
      </div>
      <div class="card-body card-body-padding">
        <div class="d-flex">
          <p class="d-flex flex-column">
            <span class="text-bold text-lg">${{ json_encode($monthlyreport['currentYearlyDataTotal'])}}</span>
            <span>Order Over Time</span>
          </p>
          <p class="ml-auto d-flex flex-column text-right">
            <span class="text-success alignLeft">
               <i class="fas {{ $monthlyreport['YearlyProgress']=='up'? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{number_format((float)$monthlyreport['YearlyProgressPercentage'], 2, '.', '')}}%
            </span>
            <span class="text-muted">Since last month</span>
          </p>
        </div>
        <div class="position-relative mb-4">
          <div class="chartjs-size-monitor">
            <div class="chartjs-size-monitor-expand">
              <div class=""></div>
            </div>
            <div class="chartjs-size-monitor-shrink">
              <div class=""></div>
            </div>
          </div>
          <canvas id="sales-chart" height="200" style="display: block; width: 487px; height: 300px;" width="487" class="chartjs-render-monitor"></canvas>
        </div>
        <div class="d-flex flex-row justify-content-end">
          <span class="mr-2 paddingRight">
            <i class="fas fa-square text-primary"></i> This year
          </span>
          <span>
            <i class="fas fa-square text-gray"></i> Last year
          </span>
        </div>
      </div>
    </div>
  </div>
    <!-- /.col-md-6 -->
</div>
@endif

@if($checkShopType == 'both')
<div class="row">
  {{-- today sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($todaySaleTotal->sum) ? number_format($todaySaleTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __("TODAY'S SALES")}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-cart-arrow-down"></i>
      </div>
    </div>
  </div>

  {{-- month's sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($monthSaleTotal->sum) ? number_format($monthSaleTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('MONTH\'S SALES')}}</p>
      </div>
      <div class="icon">
        <i class="far fa-calendar-alt"></i>
      </div>
    </div>
  </div>

  {{-- year's sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($yearSaleTotal->sum) ? number_format($yearSaleTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('YEAR\'S SALES')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-sort-amount-up"></i>
      </div>
    </div>
  </div>

  {{-- total sale data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
         <h3>{{number_format($saleTotal->sum,2)}}</h3>
          <p class="innerBoxMargin">{{ __('TOTAL\'S SALES')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-calculator"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  {{-- today order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($todayOrderTotal->sum) ? number_format($todayOrderTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __("TODAY'S ORDER")}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-bag"></i>
      </div>
    </div>
  </div>

  {{-- month's order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($monthOrderTotal->sum) ? number_format($monthOrderTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('MONTH\'S ORDER')}}</p>
      </div>
      <div class="icon">
        <i class="far fa-calendar-alt"></i>
      </div>
    </div>
  </div>

  {{-- year's order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
        <h3>{{!empty($yearOrderTotal->sum) ? number_format($yearOrderTotal->sum,2) : 0}}</h3>
        <p class="innerBoxMargin"> {{ __('YEAR\'S ORDER')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-clipboard-list"></i>
      </div>
    </div>
  </div>

  {{-- total order data --}}
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info smallBoxHeight">
      <div class="inner">
         <h3>{{number_format($orderTotal->sum,2)}}</h3>
          <p class="innerBoxMargin">{{ __('TOTAL\'S ORDER')}}</p>
      </div>
      <div class="icon">
        <i class="fas fa-chart-line"></i>
      </div>
    </div>
  </div>
</div>

@endif
@endsection
@section('css')
<style type="text/css">
  .card-title
{
  color:white;
}
</style>

@endsection
@section('js')
<script type="text/javascript">
  var currentWeeklyDates;var currentWeeklyData;var oldWeeklyData;var currentWeeklyDataTotal;
  var currentMonthlyLabels;
  var shopType = "<?php echo $checkShopType;?>";
 
  if(shopType !='both' && shopType !="null")
  { 
    window.currentWeeklyDates = <?php echo array_key_exists('currentWeeklyDates', $weeklyreport) ?  json_encode($weeklyreport['currentWeeklyDates']): json_encode(['A','B','C','D','E','F','G']); ?>;
    window.currentWeeklyData = <?php echo array_key_exists('currentWeeklyData', $weeklyreport) ? json_encode($weeklyreport['currentWeeklyData']):json_encode([0,0,0,0,0,0,0]); ?>;
    window.oldWeeklyData = <?php echo array_key_exists('oldWeeklyData', $weeklyreport) ? json_encode($weeklyreport['oldWeeklyData']):json_encode([0,0,0,0,0,0,0]); ?>;
    window.currentMonthlyLabels = <?php echo array_key_exists('monthlyLabel', $monthlyreport) ? json_encode($monthlyreport['monthlyLabel']):json_encode(['A','B','C','D','E','F']);?>;

    if(shopType == 'retails')
    {
      var currentMonthlySaleData;var lastyearMonthlySaleData;
      window.currentMonthlySaleData = <?php echo(array_key_exists('currentMonthlySaleData', $monthlyreport) ? json_encode($monthlyreport['currentMonthlySaleData']): json_encode([0,0,0,0,0,0])); ?>;
      window.lastyearMonthlySaleData = <?php echo(array_key_exists('lastyearMonthlySaleData', $monthlyreport) ?json_encode($monthlyreport['lastyearMonthlySaleData']) : json_encode([0,0,0,0,0,0])); ?>;
    } else 
    {
      var currentMonthlyOrderData;var lastyearMonthlyOrderData;
      window.currentMonthlyOrderData = <?php echo(array_key_exists('currentMonthlyOrderData', $monthlyreport) ? json_encode($monthlyreport['currentMonthlyOrderData']): json_encode([0,0,0,0,0,0])); ?>;
      window.lastyearMonthlyOrderData = <?php echo(array_key_exists('lastyearMonthlyOrderData', $monthlyreport) ? json_encode($monthlyreport['lastyearMonthlyOrderData']): json_encode([0,0,0,0,0,0])); ?>;
    } 
  
  } 
  
</script>
@if($checkShopType !=NULL)
<script src="{{ asset('js/dashboard.js') }}"></script>
@endif
@endsection

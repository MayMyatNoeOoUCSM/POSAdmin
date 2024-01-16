<!-- header -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-info">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      @foreach (config('app.available_locales') as $locale)
        <li class="nav-item">
            <a class="nav-link"
              href="{{ \Illuminate\Support\Facades\Route::current()->parameters() ? (route(\Illuminate\Support\Facades\Route::currentRouteName(), \Illuminate\Support\Facades\Route::current()->parameters()).'?'.http_build_query(['lang'=>$locale])) : route(\Illuminate\Support\Facades\Route::currentRouteName(), ['lang' => $locale]) }}"
                @if (app()->getLocale() == $locale) style="font-weight: bold; text-decoration: underline" @endif>{{ strtoupper($locale) }}</a>
        </li>
      @endforeach
    </li>

    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" id="checknoti">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications->count() }} 
            </span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <span class="dropdown-item dropdown-header">
          {{ auth()->user()->unreadNotifications->count() }} Notifications
        </span>
        @foreach(auth()->user()->unreadNotifications as $notification)
          <div class="dropdown-divider"></div>
          @if($notification->data['type'] == config("constants.NOTIFICATION_LOW_STOCK"))
          <a href="{{ route('stock', array('less_stock' => 1)) }}" class="dropdown-item">
            <i class="fas fa-exclamation-triangle mr-2"></i>  {{ $notification->data['message'] }}
          </a>
          @endif
          @if($notification->data['type'] == config("constants.NOTIFICATION_DAMAGE_LOSS"))
          <a href="{{route('damageloss')}}" class="dropdown-item">
            <i class="fas fa-minus-circle mr-2"></i> {{$notification->data['message']}}
          </a>
          @endif
          @if($notification->data['type'] == config("constants.NOTIFICATION_SALE_RETURN"))
          <a href="{{route('sale_return')}}" class="dropdown-item">
            <i class="fas fa-undo mr-2"></i> {{$notification->data['message']}}
          </a>
          @endif
          @if($notification->data['type'] == config("constants.NOTIFICATION_NEW_PRODUCTS"))
          <a href="{{ route('product', app()->getLocale()) }}" class="dropdown-item">
            <i class="fas fa-barcode mr-2"></i> {{$notification->data['message']}}
          </a>
          @endif
           @if($notification->data['type'] == config("constants.NOTIFICATION_COMPANY_LICENSE_EXPIRE"))
          <a href="#" class="dropdown-item">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{$notification->data['message']}}
          </a>
          @endif
        @endforeach
    
      
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-user mr-2"> {{ Auth::user()->name }}</i>
      </a>
    </li>
  </ul>
</nav>
<script type="text/javascript">
  $(document).ready(function(){
    $("#checknoti").on('click',function(){
       $.get("/markAsRead");
       $("span.badge.badge-warning.navbar-badge").text('0');
    })
  })
</script>
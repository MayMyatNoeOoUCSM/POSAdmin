<!-- sidebar -->
<aside class="main-sidebar sidebar-light-danger elevation-2">
  <a href="{{route('dashboard')}}" class="brand-link navbar-light">
    <img src="{{asset('img/dashboard_logo.png')}}" alt="SCHOOL" class="brand-image">
    <span class="brand-text">POS Admin</span>
  </a>
  <div class="sidebar">
    <nav class="mt-4">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item {{ (request()->is('dashboard')) ? 'menu-active' : '' }}">
          <a href="{{route('dashboard')}}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>{{ __('Dashboard') }}</p>
          </a>
        </li>
        @if (Auth::guard('staff')->user()->role == config('constants.ADMIN') )
        <li class="nav-item {{ (request()->segment(1)=='company' ? (request()->segment(2)!='license' && request()->segment(2)!='report'? 'menu-active':''):'') }}">
          <a href="{{ route('company') }}" class="nav-link">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>{{ __('Company') }}</p>
          </a>
        </li>
        @endif
        @if (Auth::guard('staff')->user()->role == config('constants.ADMIN') )
        <li class="nav-item {{ (request()->segment(2)=='license') ? 'menu-active' : '' }}">
          <a href="{{ route('company.license') }}" class="nav-link">
            <i class="nav-icon fas fa-copyright"></i>
            <p>{{ __('Company License') }}</p>
          </a>
        </li>
        @endif
        @if (checkShopOwnerType()=='both' || checkShopOwnerType()=='retails' AND Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN'))
        <li class="nav-item {{ (request()->is('warehouse*')) ? 'menu-active' : '' }}">
          <a href="{{ route('warehouse') }}" class="nav-link">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>{{ __('Warehouse') }}</p>
          </a>
        </li>
        @endif
        @if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN'))
        <li class="nav-item {{ (request()->is('shop*')) ? 'menu-active' : '' }}">
          <a href="{{ route('shop') }}" class="nav-link">
            <i class="nav-icon fas fa-store"></i>
            <p>{{ __('Shop') }}</p>
          </a>
        </li>
        @endif
        @if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN') OR Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN'))
        <li class="nav-item {{ (request()->is('terminal*')) ? 'menu-active' : '' }}">
          <a href="{{ route('terminal') }}" class="nav-link">
            <i class="nav-icon fas fa-solar-panel"></i>
            <p>{{ __('Terminal') }}</p>
          </a>
        </li>
        @endif
         @if (checkShopOwnerType()=='both' OR checkShopOwnerType()=='restaurant')
        <li class="nav-item {{ (request()->is('restaurant*')) ? 'menu-active' : '' }}">
          <a href="{{ route('restaurant') }}" class="nav-link">
            <i class="nav-icon fas fa-dumpster"></i>
            <p>{{ __('Restaurant Table') }}</p>
          </a>
        </li>
        @endif
         @if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN') OR Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN'))
        <li class="nav-item {{ (request()->is('category*')) ? 'menu-active' : '' }}">
          <a href="{{ route('category') }}" class="nav-link">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>{{ __('Category') }}</p>
          </a>
        </li>
       
        <li class="nav-item {{ (request()->is('product*')) ? 'menu-active' : '' }}">
          <a href="{{ route('product') }}" class="nav-link">
            <i class="nav-icon fas fa-cube"></i>
            <p>{{ __('Product') }}</p>
          </a>
        </li>
        @endif
        @if (checkShopOwnerType()=='both' OR checkShopOwnerType()=='retails')
        <li class="nav-item {{ (request()->is('stock*')) ? 'menu-active' : '' }}">
          <a href="{{ route('stock') }}" class="nav-link">
            <i class="nav-icon fas fa-cubes"></i>
            <p>{{ __('Stock') }}</p>
          </a>
        </li>
        <li class="nav-item {{ (request()->segment(1)=='sale') ? 'menu-active' : '' }}"> 
          <a href="{{route('sale')}}" class="nav-link">
            <i class="nav-icon fas fa-cart-arrow-down"></i>
            <p>{{ __('Sales') }}</p>
          </a>
        </li>
        @endif
         @if (checkShopOwnerType()=='both' OR checkShopOwnerType()=='restaurant')
        <li class="nav-item {{ (request()->segment(1)=='order') ? 'menu-active' : '' }}"> 
          <a href="{{route('order')}}" class="nav-link">
            <i class="nav-icon fas fa-shopping-bag"></i>
            <p>{{ __('Order') }}</p>
          </a>
        </li>
        @endif

        @if (checkShopOwnerType()=='both' OR checkShopOwnerType()=='retails')
        <li class="nav-item {{ (request()->is('sale_return*')) ? 'menu-active' : '' }}">
          <a href="{{ route('sale_return') }}" class=" nav-link">
            <i class="nav-icon fas fa-share-square"></i>
            <p>{{ __('Sales Return') }}</p>
          </a>
        </li>
        <li class="nav-item {{ (request()->is('damage_loss*')) ? 'menu-active' : '' }}">
          <a href="{{ route('damageloss') }}" class="nav-link">
            <i class="nav-icon fas fa-minus-circle" style="color:red"></i>
            <p>{{ __('Damage And Loss') }}</p>
          </a>
        </li>
        @endif
        
        @if (Auth::guard('staff')->user()->role == config('constants.ADMIN'))
        <li class="nav-item {{ (request()->is('staff*')) ? 'menu-active' : '' }}">
          <a href="{{ route('staff') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>{{ __('User') }}</p>
          </a>
        </li>
        @endif
        <li class="nav-item {{ (request()->is('*report')) ? 'menu-open' : '' }}">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-file-excel"></i>
            <p>{{ __('Report') }}
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
        

            @if (checkShopOwnerType()=='both' OR checkShopOwnerType()=='retails')
          <ul class="nav nav-treeview" style="{{ (request()->is('*report')) ? 'display:block' : 'display:none' }}">
            <li class="nav-item {{ (request()->is('sale/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('sale.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Sale Report') }}</p>
              </a>
            </li>
           <!--  <li class="nav-item {{ (request()->is('invoice/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('invoice.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Invoice Report') }}</p>
              </a>
            </li>
            <li class="nav-item {{ (request()->is('invoice/details/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('invoice.details.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Invoice Details Report') }}</p>
              </a>
            </li> -->
            <li class="nav-item {{ (request()->is('salereturn/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('salereturn.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Sale Return Report') }}</p>
              </a>
            </li>
            <li class="nav-item {{ (request()->is('damageloss/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('damageloss.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Damage Loss Report') }}</p>
              </a>
            </li>
            <li class="nav-item {{ (request()->is('salecategory/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('salecategory.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Sale Category Report') }}</p>
              </a>
            </li>
            <li class="nav-item {{ (request()->is('saleproduct/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('saleproduct.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Sale Product Report') }}</p>
              </a>
            </li>
            <li class="nav-item {{ (request()->is('top_saleproduct/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('topsale.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Top Ten Sale Product') }}</p>
                <p style="margin-left: 35px;float: left;">{{ __('Report')}}</p>
              </a>
            </li>
            <li class="nav-item {{ (request()->is('inventory_product/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('inventoryproduct.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Inventory Product') }}</p>
                <p style="margin-left: 35px;float: left;">{{ __('Report')}}</p>
              </a>
            </li>
             <li class="nav-item {{ (request()->is('inventory_category/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('inventorycategory.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Inventory Category') }}</p>
                <p style="margin-left: 35px;float: left;">{{ __('Report')}}</p>
              </a>
            </li>
          </ul>
          @elseif(Auth::guard('staff')->user()->role == config('constants.ADMIN'))
          <ul class="nav nav-treeview" style="{{ (request()->is('*report')) ? 'display:block' : 'display:none' }}">
            <li class="nav-item {{ (request()->is('company/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('company.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Company Report') }}</p>
              </a>
            </li>
            <li class="nav-item {{ (request()->is('companylicense/report')) ? 'menu-active' : '' }}">
              <a href="{{ route('companylicense.report') }}" class="nav-link">
                <i class="nav-icon fas fa-file-excel"></i>
                <p>{{ __('Company License Report') }}</p>
              </a>
            </li>
          </ul>
          @endif

        </li>
      
        <li class="nav-item">
          <a href="{{ route('logout') }}" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>{{ __('Logout') }}</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
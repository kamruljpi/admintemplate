<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('media/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('media/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @if(count(generateMenu()) > 0)
          @foreach(generateMenu() as $sl=>$menu)
            <li class="nav-item {{ (isset($menu['subMenu']) && !empty($menu['subMenu']) && count($menu['subMenu']) > 0) ? 'has-treeview' : '' }}">
              
                <a href="{{ (isset($menu['subMenu']) && !empty ($menu['subMenu'])) ? '#': (($menu['route_name'])? Route::has($menu['route_name']) ? Route($menu['route_name']) : '#' :'#') }}" 
                    class="nav-link" >
                    <i class="nav-icon fas {{ $menu['icon'] }}"></i>
                    <p>
                      {{ $menu['name'] }}
                      {!! (isset($menu['subMenu']) && !empty($menu['subMenu']) && count($menu['subMenu']) > 0) ? '<i class="fas fa-angle-left right"></i>' : '' !!}
                    </p>
                </a>
                @if(isset($menu['subMenu']) && !empty($menu['subMenu']))
                <ul class="nav nav-treeview">
                    @foreach($menu['subMenu'] as $sl=>$sub_menu)
                        @php
                            $explode_route = explode("_",$sub_menu['route_name']);
                            $action_route = $explode_route[count($explode_route)-1];
                        @endphp
                        @if($action_route!='action')
                            {{-- <li class="{{ (Route::current()->getName() == $sub_menu['route_name']) ? 'active' : '' }}"> --}}
                              <li class="nav-item">

                                <a href="{{ ($sub_menu['route_name'])? Route::has($sub_menu['route_name']) ? Route($sub_menu['route_name']) : '#' :'' }}" class="nav-link"><i class="far fa-circle nav-icon"></i><p>{{$sub_menu['name']}}</p></a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                @endif
            </li>
          @endforeach
         @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
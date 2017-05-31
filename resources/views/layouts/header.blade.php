<!-- Main Header -->
<header class="main-header">

  <!-- Logo -->
  <a href="/dashboard" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>A</b>LT</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>Admin</b>LTE</span>
  </a>

  <!-- Header Navbar -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- User Account Menu -->
        <li class="dropdown user user-menu">
          <!-- Menu Toggle Button -->
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <!-- The user image in the navbar-->
            <img src="{{ asset("/bower_components/admin-lte/dist/img/avatar.png") }}" class="user-image" alt="User Image">
            <!-- hidden-xs hides the username on small devices so only the image appears. -->
            <span class="hidden-xs">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <img src="{{ asset("/bower_components/admin-lte/dist/img/avatar.png") }}" class="img-circle" alt="User Image">

              <p>
                {{ Auth::user()->name }}
                <small>{{ Auth::user()->roles[0]['display_name'] }}</small>
              </p>
            </li>

            <!-- inner menu: contains the actual data -->
            @role(['super','admin'])
            <li>
              <a href="{{ route('user.add') }}">
                <i class="fa fa-users text-aqua"></i>
                <i style="color: black">添加用户</i>
              </a>
            </li>
            @endrole
            <li>
              <a href="{{ route('user.reset') }}">
                <i class="fa fa-warning text-yellow"></i>
                <i style="color: black">修改密码</i>
              </a>
            </li>
            @if(!empty($accounts))
            <!-- Menu Body -->
            <li class="user-body">
              <div class="row">
                @foreach ($accounts as $account)
                  <div class="col-xs-4">
                    @if($account['type'] == 1)
                      <span style="font-size: 12px">钻石</span>
                      @elseif($account['type'] == 2)
                      <span style="font-size: 12px">房卡</span>
                    @elseif($account['type'] == 3)
                      <span style="font-size: 12px">欢乐豆</span>
                    @else
                    @endif
                    <span style="color: red;font-size: 12px"><i>{{ $account['balance'] }}</i></span>
                  </div>
                @endforeach
              </div>
              <!-- /.row -->
            </li>
            @endif
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <!--a href="#" class="btn btn-default btn-flat">Profile</a-->
              </div>
              <div class="pull-right">
                <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">注销</a>
              </div>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>


            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
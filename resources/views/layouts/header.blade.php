<!-- Main Header -->
<header class="main-header">

  <!-- Logo -->
  <a href="/dashboard" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <!--span class="logo-mini"><b>A</b>LT</span-->
    <!-- logo for regular state and mobile devices -->
    <img style="width: auto; height: 62%;" src="{{ asset("/bower_components/admin-lte/dist/img/bahe_logo.png") }}"/>
    <!--span class="logo-lg"><b>Admin</b>LTE</span-->
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
            <span class="hidden-xs">{{ Auth::user()->user_name }}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- The user image in the menu -->
            <li class="user-header">
              <img src="{{ asset("/bower_components/admin-lte/dist/img/avatar.png") }}" class="img-circle" alt="User Image">

              <p>
                {{ Auth::user()->user_name }}
                <small>{{ Auth::user()->roles[0]['display_name'] }}</small>
              </p>
            </li>

            <!-- inner menu: contains the actual data -->
            @role(['super','admin'])
            @role('super')
            <li>
              <a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_ADMIN]) }}">
                <i class="fa fa-users text-aqua"></i>
                <i style="color: black">添加管理员</i>
              </a>
            </li>
            @endrole
            <li>
              <a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_AGENT]) }}">
                <i class="fa fa-users text-aqua"></i>
                <i style="color: black">添加代理</i>
              </a>
            </li>
            <li>
              <a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_FIRST_AGENT]) }}">
                <i class="fa fa-users text-aqua"></i>
                <i style="color: black">添加总监</i>
              </a>
            </li>
            @endrole
            <li>
              <a href="{{ route('user.reset') }}">
                <i class="fa fa-warning text-yellow"></i>
                <i style="color: black">修改密码</i>
              </a>
            </li>
            @if(!empty($account) && (!empty($account['diamond_balance']) ||
                !empty($account['card_balance']) || !empty($account['bean_balance'])))
            <!-- Menu Body -->
            <li class="user-body">
              <div class="row">
                  <div class="col-xs-4">
                    @if($account['diamond_balance'] > 0)
                      <span style="font-size: 12px">钻石</span>
                      <span style="color: red;font-size: 12px"><i>{{ $account['diamond_balance'] }}</i></span>
                    @endif
                    @if($account['card_balance'] > 0)
                      <span style="font-size: 12px">房卡</span>
                      <span style="color: red;font-size: 12px"><i>{{ $account['card_balance'] }}</i></span>
                    @endif
                    @if($account['bean_balance'])
                      <span style="font-size: 12px">欢乐豆</span>
                      <span style="color: red;font-size: 12px"><i>{{ $account['bean_balance'] }}</i></span>
                    @endif
                  </div>
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
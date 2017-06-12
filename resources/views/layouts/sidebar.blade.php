<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset("/bower_components/admin-lte/dist/img/avatar.png") }}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ Auth::user()->name }}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <!--form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form-->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">HEADER</li>
        <!-- Optionally, you can add icons to the links -->
        <li id="recharge" class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>充值中心</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @role(['super', 'admin'])
            <li id="agent_recharge"><a href="{{ route('recharge.agent') }}"><i class="fa fa-link"></i> <span>代理充值</span></a></li>
            @endrole
            <li id="user_recharge"><a href="{{ route('recharge.user') }}"><i class="fa fa-link"></i> <span>用户充值</span></a></li>
          </ul>
        </li>
        @role(['super', 'admin'])
        <li id="general_agent" class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>一级代理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="general_agent_add"><a href="{{ route('general_agent.add') }}"><i class="fa fa-circle-o"></i>新增</a></li>
            <li id="general_agent_list"><a href="{{ route('general_agent.list') }}"><i class="fa fa-circle-o"></i>查询</a></li>
            <li id="general_agent_banlist"><a href="{{ route('general_agent.banlist') }}"><i class="fa fa-circle-o"></i>封禁查询</a></li>
            <li id="general_agent_invite_code"><a href="{{ route('general_agent.invite_code') }}"><i class="fa fa-circle-o"></i>邀请码</a></li>
          </ul>
        </li>
        <li id="agent" class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>代理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="agent_list"><a href="{{ route('agent.list') }}"><i class="fa fa-circle-o"></i>查询</a></li>
            <li id="agent_banlist"><a href="{{ route('agent.banlist') }}"><i class="fa fa-circle-o"></i>封禁查询</a></li>
          </ul>
        </li>
        <!--li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>玩家</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="active"><a href="index2.html"><i class="fa fa-circle-o"></i>信息查询</a></li>
          </ul>
        </li-->
        @endrole
        <li id="openroom" class="treeview">
          <a href="{{ route('agent.openroom') }}">
            <i class="fa fa-link"></i> <span>代开房</span>
          </a>
        </li>
        <!--li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>数据</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.html"><i class="fa fa-circle-o"></i>流水汇总</a></li>
          </ul>
        </li-->
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
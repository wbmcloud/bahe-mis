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
          <p>{{ Auth::user()->user_name }}</p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

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
            <li id="agent_recharge"><a href="{{ route('recharge.agent') }}"><i class="fa fa-circle-o"></i> <span>代理充值</span></a></li>
            @endrole
            <li id="user_recharge"><a href="{{ route('recharge.user') }}"><i class="fa fa-circle-o"></i> <span>用户充值</span></a></li>
          </ul>
        </li>
        @role(['super', 'admin'])
        <li id="first_agent" class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>一级代理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="first_agent_add"><a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_FIRST_AGENT]) }}"><i class="fa fa-circle-o"></i>新增</a></li>
            <li id="first_agent_list"><a href="{{ route('first_agent.list') }}"><i class="fa fa-circle-o"></i>查询</a></li>
            <li id="first_agent_banlist"><a href="{{ route('first_agent.banlist') }}"><i class="fa fa-circle-o"></i>封禁查询</a></li>
            <li id="first_agent_invite_code"><a href="{{ route('first_agent.invite_code') }}"><i class="fa fa-circle-o"></i>邀请码</a></li>
            <li id="first_agent_cash_order"><a href="{{ route('first_agent.cash_order_list') }}"><i class="fa fa-circle-o"></i>每周打款单</a></li>
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
            <li id="agent_add"><a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_AGENT]) }}"><i class="fa fa-circle-o"></i>新增</a></li>
            <li id="agent_list"><a href="{{ route('agent.list') }}"><i class="fa fa-circle-o"></i>查询</a></li>
            <li id="agent_banlist"><a href="{{ route('agent.banlist') }}"><i class="fa fa-circle-o"></i>封禁查询</a></li>
          </ul>
        </li>
        @endrole
        <li id="openroom" class="treeview">
          <a href="{{ route('agent.openroom') }}">
            <i class="fa fa-link"></i> <span>代开房</span>
          </a>
        </li>
        @role(['agent', 'first_agent'])
        <li id="agent_consume_flow" class="treeview">
          <a href="{{ route('agent.rechargelist', ['id' => \Illuminate\Support\Facades\Auth::id()]) }}">
            <i class="fa fa-circle-o"></i><span>消费记录</span>
          </a>
        </li>
        @endrole
        @role(['first_agent'])
        <li id="first_agent_rechargelist" class="treeview">
          <a href="{{ route('first_agent.rechargelist', ['invite_code' => \Illuminate\Support\Facades\Auth::user()->invite_code]) }}">
          <i class="fa fa-circle-o"></i><span>代理充值记录</span>
          </a>
        </li>
        <li id="data_stat" class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>数据统计</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="income"><a href="{{ route('first_agent.income') }}"><i class="fa fa-circle-o"></i>收入统计</a></li>
          </ul>
        </li>
        @endrole
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
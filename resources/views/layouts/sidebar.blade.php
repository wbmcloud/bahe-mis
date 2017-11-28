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
          <a href="#"><i class="fa fa-money"></i><span>充值中心</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @role(['super', 'admin'])
            <li id="agent_recharge"><a href="{{ route('recharge.agent') }}"><i class="fa fa-circle"></i><span>代理充值</span></a></li>
            @endrole
            <li id="user_recharge"><a href="{{ route('recharge.user') }}"><i class="fa fa-circle"></i><span>用户充值</span></a></li>
          </ul>
        </li>
        <li id="openroom" class="treeview">
          <a href="{{ route('agent.openroom') }}">
            <i class="fa fa-home"></i><span>代开房</span>
          </a>
        </li>
        @role(['super', 'admin'])
        <li id="agent" class="treeview">
          <a href="#">
            <i class="fa fa-group"></i><span>代理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="agent_add"><a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_AGENT]) }}"><i class="fa fa-circle"></i>新增</a></li>
            <li id="agent_list"><a href="{{ route('agent.list') }}"><i class="fa fa-circle"></i>查询</a></li>
            <li id="agent_banlist"><a href="{{ route('agent.banlist') }}"><i class="fa fa-circle"></i>封禁查询</a></li>
          </ul>
        </li>
        <li id="first_agent" class="treeview">
          <a href="#">
            <i class="fa fa-user"></i><span>总代理</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="first_agent_add"><a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_FIRST_AGENT]) }}"><i class="fa fa-circle"></i>新增</a></li>
            <li id="first_agent_list"><a href="{{ route('first_agent.list') }}"><i class="fa fa-circle"></i>查询</a></li>
            <li id="first_agent_banlist"><a href="{{ route('first_agent.banlist') }}"><i class="fa fa-circle"></i>封禁查询</a></li>
            <li id="first_agent_invite_code"><a href="{{ route('first_agent.invite_code') }}"><i class="fa fa-circle"></i>邀请码</a></li>
            <li id="first_agent_cash_order"><a href="{{ route('first_agent.cash_order_list') }}"><i class="fa fa-circle"></i>每周打款单</a></li>
          </ul>
        </li>
        <li id="general_agent" class="treeview">
          <a href="#">
            <i class="fa fa-user-secret"></i><span>总监</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="general_agent_add"><a href="{{ route('user.add', ['type' => \App\Common\Constants::ADD_USER_TYPE_GENERAL_AGENT]) }}"><i class="fa fa-circle"></i>新增</a></li>
            <li id="general_agent_list"><a href="{{ route('general_agent.list') }}"><i class="fa fa-circle"></i>查询</a></li>
            <li id="general_agent_banlist"><a href="{{ route('general_agent.banlist') }}"><i class="fa fa-circle"></i>封禁查询</a></li>
            <li id="general_agent_invite_code"><a href="{{ route('general_agent.invite_code') }}"><i class="fa fa-circle"></i>邀请码</a></li>
            <li id="general_agent_cash_order"><a href="{{ route('general_agent.cash_order_list') }}"><i class="fa fa-circle"></i>每周打款单</a></li>
          </ul>
        </li>
        <li id="game" class="treeview">
          <a href="#">
            <i class="fa fa-gamepad"></i><span>游戏</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="player_list"><a href="{{ route('game.playerlist') }}"><i class="fa fa-circle"></i>角色列表</a></li>
          </ul>
        </li>
        <li id="stat" class="treeview">
          <a href="#">
            <i class="fa fa-area-chart"></i><span>统计</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="stat_agent"><a href="{{ route('stat.agent') }}"><i class="fa fa-circle"></i><span>代理</span></a></li>
            <li id="stat_flow"><a href="{{ route('stat.flow') }}"><i class="fa fa-circle"></i><span>流水</span></a></li>
            <li id="stat_rounds"><a href="{{ route('stat.rounds') }}"><i class="fa fa-circle"></i><span>局数</span></a></li>
            <li id="stat_dau"><a href="{{ route('stat.dau') }}"><i class="fa fa-circle"></i><span>DAU</span></a></li>
            <li id="stat_wau"><a href="{{ route('stat.wau') }}"><i class="fa fa-circle"></i><span>WAU</span></a></li>
            <li id="stat_mau"><a href="{{ route('stat.mau') }}"><i class="fa fa-circle"></i><span>MAU</span></a></li>
          </ul>
        </li>
        @endrole
        <li id="record" class="treeview">
          <a href="#">
            <i class="fa fa-history"></i><span>记录</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            @role(['super', 'admin'])
            <li id="agent_recharge_record"><a href="{{ route('record.agentrecharge') }}"><i class="fa fa-circle"></i><span>代理充值记录</span></a></li>
            <li id="user_recharge_record"><a href="{{ route('record.userrecharge') }}"><i class="fa fa-circle"></i><span>用户充值记录</span></a></li>
            @endrole
            <li id="open_room_record"><a href="{{ route('record.openroom') }}"><i class="fa fa-circle"></i><span>代开房记录</span></a></li>
            @role(['agent', 'first_agent', 'general_agent'])
            <li id="agent_consume_flow" class="treeview">
              <a href="{{ route('agent.rechargelist', [
                  'id' => \Illuminate\Support\Facades\Auth::id(),
                  'start_date' => \Carbon\Carbon::now()->startOfWeek()->toDateString(),
                  'end_date' => \Carbon\Carbon::tomorrow()->toDateString()
                ]) }}">
                <i class="fa fa-circle"></i><span>消费记录</span>
              </a>
            </li>
            @endrole
            @role(['first_agent'])
            <li id="first_agent_rechargelist" class="treeview">
              <a href="{{ route('first_agent.rechargelist', [
                  'invite_code' => \Illuminate\Support\Facades\Auth::user()->code,
                  'start_date' => \Carbon\Carbon::now()->startOfWeek()->toDateString(),
                  'end_date' => \Carbon\Carbon::tomorrow()->toDateString()
                ]) }}">
                <i class="fa fa-circle"></i><span>代理充值记录</span>
              </a>
            </li>
            @endrole
            @role(['general_agent'])
            <li id="agent_rechargelist" class="treeview">
              <a href="{{ route('general_agent.rechargelist', [
                    'invite_code' => \Illuminate\Support\Facades\Auth::user()->code,
                    'start_date' => \Carbon\Carbon::now()->startOfWeek()->toDateString(),
                    'end_date' => \Carbon\Carbon::tomorrow()->toDateString()
                ]) }}">
                <i class="fa fa-circle"></i><span>代理充值记录</span>
              </a>
            </li>
            <li id="first_agent_rechargelist" class="treeview">
              <a href="{{ route('general_agent.first_agent_rechargelist', ['invite_code' => \Illuminate\Support\Facades\Auth::user()->code]) }}">
                <i class="fa fa-circle"></i><span>总代理销售记录</span>
              </a>
            </li>
            @endrole
          </ul>
        </li>
        @role(['first_agent'])
        <li id="data_stat" class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart"></i><span>数据统计</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="income"><a href="{{ route('first_agent.income') }}"><i class="fa fa-circle"></i>收入统计</a></li>
            <li id="sale"><a href="{{ route('first_agent.sale') }}"><i class="fa fa-circle"></i>本周账单明细</a></li>
            <li id="history"><a href="{{ route('first_agent.income_history') }}"><i class="fa fa-circle"></i>历史收入查询</a></li>
          </ul>
        </li>
        @endrole
        @role(['general_agent'])
        <li id="data_stat" class="treeview">
          <a href="#">
            <i class="fa fa-bar-chart"></i> <span>数据统计</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li id="income"><a href="{{ route('general_agent.income') }}"><i class="fa fa-circle"></i>收入统计</a></li>
            <li id="first_agent_sale"><a href="{{ route('general_agent.sale', ['type' => \App\Common\Constants::ROLE_TYPE_FIRST_AGENT]) }}"><i class="fa fa-circle"></i>本周总代理账单明细</a></li>
            <li id="agent_sale"><a href="{{ route('general_agent.sale', ['type' => \App\Common\Constants::ROLE_TYPE_AGENT]) }}"><i class="fa fa-circle"></i>本周代理账单明细</a></li>
            <li id="history"><a href="{{ route('general_agent.income_history') }}"><i class="fa fa-circle"></i>历史收入查询</a></li>
          </ul>
        </li>
        @endrole
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>
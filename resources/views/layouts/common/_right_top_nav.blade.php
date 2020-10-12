<ul class="nav navbar-top-links navbar-right">
    <li>
        <span class="m-r-sm text-muted welcome-message">欢迎 {{session('AdminUser')->username}} 来到financial管理后台</span>
    </li>
    <li>
        <a href="{{url('admin/index')}}">
            <i class="fa fa-home"></i> 后台首页
        </a>
    </li>
    <li>
        <a href="/admin/system/repassword/{{session('AdminUser')->id}}">
            <i class="fa fa-edit"></i> 修改密码
        </a>
    </li>

    <li>
        <a href="{{url('admin/logout')}}" class="js-ajax-status">
            <i class="fa fa-sign-out"></i> 退出
        </a>
    </li>
</ul>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="/img/profile_small.png"/>
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong
                                            class="font-bold">{{session('AdminUser')->username}}</strong>
                             </span> <span class="text-muted text-xs block">{{session('AdminUser')->org_id}} <b
                                            class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="/admin/system/repassword/{{session('AdminUser')->id}}">修改密码</a></li>
                        <li><a href="{{url('admin/logout')}}" class="js-ajax-status">退出</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img alt="image" class="img-circle" src="/img/profile_small.png"/>
                </div>
            </li>

            {{--菜单生成--}}
            {!! session('menuStr') !!}
        </ul>

    </div>
</nav>
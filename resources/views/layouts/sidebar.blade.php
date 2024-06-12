<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link text-center">
        {{-- <img src="{{ asset('assets/dashboard/dist/img/AdminLTELogo.png') }}" alt="پنل مدیریت" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
        <img style="height: 90px; display: block; margin: auto; filter: invert(100%) sepia(0%) saturate(6920%) hue-rotate(276deg) brightness(130%) contrast(87%);" src="{{ asset('assets/dashboard/dist/img/logo/logo.png') }}" alt="">
        
        <span style="display: block; margin: auto;" class="brand-text font-weight-light "> پنل مدیریت مهدیه همدان </span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            مدیریت کاربران
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reserve.index') }}" class="nav-link">
                        <i class="nav-icon far fa-calendar"></i>
                        <p>
                            مدیریت رزرو ها
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('all-appointments') }}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            مدیریت نوبت ها
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/dashboard/settings/privacy-policy/edit') }}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            شرایط و قوانین
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">@csrf</form>
                    <a href="#!" onclick="document.getElementById('logout-form').submit()" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            خروج
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>



<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        @if(isSuperAdmin() && count(authUser()) > 0 && authUser()->user_type != 2)
            {{-- Menu for super admin --}}
            <ul class="nav" id="side-menu">
                <li><a href="{!! route('company.index') !!}"><i class="fa fa-briefcase"></i><span> {!! lang('company.manage_company') !!}</span> </a> </li>
                <li><a href="{!! route('role.index') !!}"><i class="fa fa-user"></i><span> {!! lang('role.role') !!}</span> </a> </li>
                <li><a href="{!! route('user.index') !!}"><i class="fa fa-user"></i><span> {!! lang('user.user') !!}</span> </a> </li>
                <li><a href="{!! route('menu.index') !!}"><i class="fa fa-bars"></i><span> {!! lang('menu.menu') !!}</span> </a> </li>
            </ul>
        @elseif(count(authUser()) > 0 && authUser()->user_type == 2)
            <ul class="nav" id="side-menu">
                <li><a href="{!! route('home') !!}"><i class="fa fa-dashboard"></i><span>{!! lang('common.dashboard') !!}</span> </a> </li>
            </ul>
        @else
            <?php $menus = renderMenus(); ?>
            <ul class="nav" id="side-menu">
                @if(count($menus) > 0)
                    @foreach($menus as $key => $data)
                        <li class="<?php echo (array_key_exists('child', $data))?'dropdown':''; ?>" >
                            <a href="{{($data['route'] !='') ? route($data['route']) : '#' }}" class="<?php echo (array_key_exists('child', $data))?'dropdown-toggle':'';  ?>" data-toggle="<?php echo (array_key_exists('child', $data))?'dropdown':'';  ?>"  ><i class="{!! $data['icon'] !!}"></i>
                 <span>
                 {!! $data['name'] !!}
                     @if(array_key_exists('child', $data))
                         <span class="fa arrow"></span>
                     @endif
                 </span>
                            </a>
                            @if(array_key_exists('child', $data))
                                <ul class="nav nav-second-level">
                                    @foreach($data['child'] as $childData)
                                        <li>
                                            <a href="{{($childData['route'] !='') ? route($childData['route']) : '#' }}">{!! $childData['name'] !!}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @endif

            </ul>

        @endif
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
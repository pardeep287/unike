@extends('layouts.admin')
@section('content')
<div id="page-wrapper">
    <!-- start: PAGE HEADER -->
    <div class="row topheading-row">
        <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12">
            <h1 class="page-header margintop10">{!! lang('common.edit_heading', lang('user.user')) !!}   #{{ $user->username }}</h1>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-3 col-xs-12">
            <a class="btn btn-sm btn-default pull-right margintop10 marginbottom10" href="{!! route('user.index') !!}"> <i class="fa fa-arrow-left fa-fw"></i> {!! lang('common.back') !!} </a>
        </div>

        <!-- /.col-lg-12 -->
    </div>
    <!-- end: PAGE HEADER -->
    <!-- start: PAGE CONTENT -->

    {{-- for message rendering --}}
    @include('layouts.messages')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0">
        {!! Form::model($user, array('route' => array('user.update', $user->id), 'method' => 'PATCH', 'id' => 'user-form', 'class' => 'form-horizontal')) !!}
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-external-link-square"></i>
                        {!! lang('user.user_detail') !!}
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('name', lang('common.name'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('name', $user->name , array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('username', lang('user.username'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('username', $user->username, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('email', lang('user.email'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('email', $user->email, array('class' => 'form-control')) !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    {!! Form::label('password', lang('user.password'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                    {!! Form::password('password' , array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                                
                                 

                                <div class="form-group">
                                    {!! Form::label('role', lang('user.role'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('role', $role , $user->role_id, array('class' => 'form-control select2')) !!}
                                    </div>
                                </div>
                                @if(isSuperAdmin())
                                <div class="form-group">
                                    {!! Form::label('company', lang('company.company'), array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('company_id', $companies, $user->company_id, array('class' => 'form-control select2' )) !!}
                                    </div>
                                </div>
                                    @else
                                    {!! Form::hidden('company_id', loggedInCompanyId()) !!}
                                @endif
                                <div class="form-group">
                                    {!! Form::label('status', lang('common.active') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}
                                    <div class="col-sm-8">
                                        <label class="checkbox col-sm-4">
                                            {!! Form::checkbox('status', '1' , ($user->status == '1') ? true : false) !!}
                                        </label>
                                    </div>
                                </div>
                                @if( isSuperAdmin())
                                    <div class="form-group">
                                        {!! Form::label('is_super_admin', lang('user.is_super_admin') . '&nbsp;', array('class' => 'col-sm-4 control-label')) !!}
                                        <div class="col-sm-8">
                                            <label class="checkbox col-sm-4">
                                               {!! Form::checkbox('is_super_admin', '1' , ($user->is_super_admin == '1') ? true : false) !!}
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-12 margintop5 clearfix text-center">
                                    <div class="form-group margin0">
                                        {!! Form::hidden('pemission_id', ($userPermissions == null)?"":$userPermissions->permission_id) !!}
                                        {!! Form::submit(lang('common.update'), array('class' => 'btn btn-danger')) !!}
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 uac-scroll">
                                @if(isset($tree))
                                    @if(count($tree) > 0)
                                        <ul id="tree">
                                            @foreach($tree as $level1)

                                                <li>
                                                    @if(array_key_exists('child', $level1))
                                                        <i class="fa fa-minus collapsee ulclose"></i>
                                                    @endif
                                                    {!! $level1['name'] !!}
                                                </li>

                                                {{-- Second level --}}
                                                @if(array_key_exists('child', $level1))
                                                    <ul>
                                                        @foreach( $level1['child'] as  $level2 )
                                                            <li>
                                                                {{-- Third level performing --}}
                                                                @if(array_key_exists('child', $level2))
                                                                    <ul>
                                                                        <li>
                                                                            <i class="fa fa-minus collapsee ulclose"></i>
                                                                            {!! $level2['name'] !!}
                                                                        </li>

                                                                        <ul>
                                                                            @foreach($level2['child'] as $level3)
                                                                                <li>

                                                                                    <input type="checkbox" name="section[]" value="{!! $level1['id'].','.$level2['id'].','.$level3['id'] !!}" <?php if(in_array($level3['id'], $detail)){ echo 'checked="checked"'; } ?> />
                                                                                    {!! $level3['name'] !!}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>

                                                                    </ul>
                                                                @else
                                                                    <input type="checkbox" name="section[]" value="{!! $level1['id'].','.$level2['id'] !!}" <?php if(in_array($level2['id'], $detail)){ echo 'checked="checked"'; } ?> />
                                                                    {!! $level2['name'] !!}
                                                                @endif

                                                                {{-- end of the third level --}}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                {{--End of the second level--}}

                                            @endforeach
                                        </ul>
                                    @endif
                                    @endif

                            </div>

                        </div>
                    </div>
                </div>
                <!-- end: TEXT FIELDS PANEL -->
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- /#page-wrapper -->
<script>
    $('i.collapsee').on('click', function() {
        if($(this).hasClass('ulclose')) {
            $(this).removeClass('ulclose').addClass('ulopen');
            $(this).parent().next('ul').removeClass('hidden');
            $(this).removeClass('fa-plus').addClass('fa-minus');
            $(this).next('a').removeClass('ulclose').addClass('ulopen');
        } else {
            $(this).removeClass('ulopen').addClass('ulclose');
            $(this).parent().next('ul').addClass('hidden');
            $(this).removeClass('fa-minus').addClass('fa-plus');
            $(this).next('a').removeClass('ulopen').addClass('ulclose');
        }
    });
    $('a.collapsee').on('click', function() {
        if($(this).hasClass('ulclose')) {
            $(this).removeClass('ulclose').addClass('ulopen');
            $(this).parent().next('ul').removeClass('hidden');
            $(this).siblings('i').removeClass('fa-plus').addClass('fa-minus');
            $(this).siblings('i').removeClass('fa-plus').addClass('fa-minus').removeClass('ulclose').addClass('ulopen');
        } else {
            $(this).removeClass('ulopen').addClass('ulclose');
            $(this).parent().next('ul').addClass('hidden');
            $(this).siblings('i').removeClass('fa-minus').addClass('fa-plus');
            $(this).siblings('i').removeClass('fa-minus').addClass('fa-plus').removeClass('ulopen').addClass('ulclose');
        }
    });
</script>
@stop

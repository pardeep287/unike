{!! Form::open(array('method' => 'POST', 'route' => array('invoice.send-email', $id), 'class' => 'form-horizontal')) !!}
<div class="col-md-12">
    <div class="form-group">
        {!! Form::label('name', lang('invoice.message'), array('class' => 'col-sm-2 padding0 font-14 control-label')) !!}
        <div class="col-sm-10">
            {!! Form::textarea('message', null, array('class' => 'form-control', 'size' => '4x4', 'placeholder' => lang('invoice.message'))) !!}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {!! Form::submit(lang('common.send_email'), array('class' => 'btn btn-primary')) !!}
      </div>
</div>
{!! Form::close() !!}
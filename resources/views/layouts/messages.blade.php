<div class="padding0 margintop0">
    @if (Session::has('success'))
        <div class="alert alert-success">
            <button data-dismiss="alert" class="close">
                &times;
            </button>
            <i class="fa fa-check-circle"></i> &nbsp;
            {!! Session::get('success') !!}
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close">
                &times;
            </button>
            <i class="fa fa-times-circle"></i> &nbsp;
            {!! Session::get('error') !!}
        </div>
    @endif
    @if (Session::has('warning'))
        <div class="alert alert-warning">
            <button data-dismiss="alert" class="close">
                &times;
            </button>
            <i class="fa fa-exclamation-triangle"></i> &nbsp;
            {!! Session::get('warning') !!}
        </div>
    @endif

    @if ($errors->count() > 0)
        <div class="col-md-12 padding0">
            <!-- if there are creation errors, they will show here -->
            <div class="alert alert-danger">
                <div class="marginbottom10">
                    <i class="fa fa-times-circle"></i> &nbsp;
                    {!! Lang::get('messages.please_fix') !!}
                </div>
                {!! Html::ul($errors->all()) !!}



            </div>
        </div>
    @endif

    <div class="col-md-6 padding0 hidden">
        <!-- if there are creation errors, they will show here -->
        <div class="alert alert-danger">
            <button data-dismiss="alert" class="close">
                &times;
            </button>
            <div class="marginbottom10">
                <i class="fa fa-times-circle"></i> &nbsp;
                {!! Lang::get('messages.please_fix') !!}
            </div>
            <ul class="error-messages">
            </ul>
        </div>
    </div>

    <div class="alert hidden">
        <button data-dismiss="alert" class="close">
            &times;
        </button>
        <div class="single-response">

        </div>
    </div>


</div>
@extends('layouts.default')

@section('content')

<div class="pb-lg-3 pt-lg-3 pb-0 pt-0">

    <div class="container-fluid main-container">

        <div class="row rowMedium">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active_Homepage">
                    
                <div class="row p-lg-5 p-md-5 ps-1 pe-1 pt-5 pb-5">

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                    @include('layouts.partials.legend_view',['is_editor' => $is_editor, 'course_code' => $course_code])

                    @if(Session::has('message'))
                    <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 all-alerts'>
                        <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">
                            @if(is_array(Session::get('message')))
                                @php $messageArray = array(); $messageArray = Session::get('message'); @endphp
                                @foreach($messageArray as $message)
                                    {!! $message !!}
                                @endforeach
                            @else
                                {!! Session::get('message') !!}
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    @endif

                    {!! isset($action_bar) ?  $action_bar : '' !!}
                    @if (isset($sub))
                    <div class='col-lg-6 col-12 d-none d-md-none d-lg-block'>
                        <div class='col-12 h-100 left-form'></div>
                    </div>
                    <div class='col-lg-6 col-12'>
                        <div class='form-wrapper form-edit p-3 rounded'>
                        
                            <form class='form-horizontal' role='form' name='mail_verification_change' method='post' action='{{ $_SERVER['SCRIPT_NAME'] }}'>
                            <fieldset>		
                          
                                    <div class='form-group mt-3'>
                                        <label class='col-sm-12 control-label-notes'>{{ trans('langChangeTo') }}:</label>
                                        <div class='col-sm-12'>
                                            {!! selection($mail_ver_data, "new_mail_ver", $sub, "class='form-control'") !!}
                                        </div>
                                    </div>
                                    {!! showSecondFactorChallenge() !!}
                             
                                    <div class='col-12 mt-5'>
                                        <input class='btn btn-sm btn-primary submitAdminBtn w-100' type='submit' name='submit' value='{{ trans('langEdit') }}'>
                                    </div>
                                    <input type='hidden' name='old_mail_ver' value='{{ $sub }}'>		
                            </fieldset>
                                {!! generate_csrf_token_form_field() !!}
                            </form>
                        </div></div>
                    @endif    
                    @include('admin.users.mail_ver_settings.messages')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
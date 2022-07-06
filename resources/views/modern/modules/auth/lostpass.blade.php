
@extends('layouts.default')

@push('head_scripts')
<script type="text/javascript" src="{{ $urlAppend }}js/pwstrength.js"></script>
<script type="text/javascript">

    var lang = {
        pwStrengthTooShort: '{!! js_escape(trans('langPwStrengthTooShort')) !!}',
        pwStrengthWeak: '{!! js_escape(trans('langPwStrengthWeak')) !!}',
        pwStrengthGood: '{!! js_escape(trans('langPwStrengthGood')) !!}',
        pwStrengthStrong: '{!! js_escape(trans('langPwStrengthStrong')) !!}',
    };

    $(document).ready(function() {
        $('#password').keyup(function() {
            $('#result').html(checkStrength($('#password').val()))
        });
    });

</script>
@endpush

@section('content')

<div class="pb-3 pt-3">

    <div class="container-fluid main-container">

        <div class="row">

            <div id="background-cheat-leftnav" class="col-xl-2 col-lg-2 col-md-0 col-sm-0 col-0 justify-content-center col_sidebar_active"> 
                <div class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block">
                    @if($course_code)
                        @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                    @else
                        @include('layouts.partials.sidebarAdmin')
                    @endif 
                </div>
            </div>

            <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active">
                    
                <div class="row p-lg-5 p-md-5 ps-1 pe-2 pt-5 pb-5">

                    <nav class="navbar navbar-expand-lg navrbar_menu_btn">
                        <button type="button" id="menu-btn" class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block btn btn-primary menu_btn_button">
                            <i class="fas fa-align-left"></i>
                            <span></span>
                        </button>
                        <a class="btn btn-primary d-lg-none mr-auto" type="button" data-bs-toggle="offcanvas" href="#collapseTools" role="button" aria-controls="collapseTools" style="margin-top:-10px;">
                            <i class="fas fa-tools"></i>
                        </a>
                    </nav>

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                    @include('layouts.partials.legend_view',['is_editor' => $is_editor, 'course_code' => $course_code])

                    <div class="offcanvas offcanvas-start d-lg-none mr-auto" tabindex="-1" id="collapseTools" aria-labelledby="offcanvasExampleLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            @if($course_code)
                                @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                            @else
                                @include('layouts.partials.sidebarAdmin')
                            @endif
                        </div>
                    </div>

                    

                    @if(Session::has('message'))
                    <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-5'>
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </p>
                    </div>
                    @endif
                    
                    {!! $action_bar !!}

                    @if(isset($_REQUEST['u']) and isset($_REQUEST['h']))
                        @if(isset($is_valid))
                            @if(isset($user_pass_updated))
                                <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                    <div class="alert alert-success"><p>{!! trans('langAccountResetSuccess1') !!}</p></div>
                                </div>
                            @elseif(isset($user_pass_notupdate))
                                <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                    <div class='alert alert-warning'>
                                        {!! implode("\n", $error_messages) !!}
                                    </div>
                                </div>
                            @endif
                            @if(!$change_ok)
                                <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                    <div class='form-wrapper shadow-sm p-3 mt-5 rounded'>
                                        
                                        <form class="form-horizontal" role="form" method='post' action='{{ $_SERVER['SCRIPT_NAME'] }}'>
                                            <input type='hidden' name='u' value='{{ $userUID }}'>
                                            <input type='hidden' name='h' value='{{ q($_REQUEST['h']) }}'>
                                            <div class="form-group mt-3">
                                                <label  class='col-sm-6 control-label-notes'>{!! trans('langNewPass1') !!}</label>
                                                <div class="col-sm-12">
                                                    <input type='password' size='40' name='newpass' value='' id='password' autocomplete='off'>&nbsp;<span id='result'></span>
                                                </div>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label class="col-sm-6 control-label-notes">{!! trans('langNewPass2') !!}</label>
                                                <div class="col-sm-12">
                                                    <input type='password' size='40' name='newpass1' value='' autocomplete='off'>
                                                </div>
                                            </div>
                                            <div class='form-group mt-3'>
                                                <div class='col-sm-offset-2 col-sm-10'>
                                                    <input class='btn btn-primary' type='submit' name='submit' value="{!! trans('langModify') !!}">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                <div class='alert alert-danger'>{!! trans('langAccountResetInvalidLink') !!}</div>
                            </div>
                        @endif

                    @elseif(isset($_POST['send_link']))
                        @if($res_first_attempt)
                            @if(!password_is_editable($res_first_attempt->password))
                                
                                <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                    <div class='alert alert-danger'>
                                        <p><strong>{!! trans('langPassCannotChange1') !!}</strong></p>
                                        <p>
                                            {!! trans('langPassCannotChange2') !!} {!! get_auth_info($auth) !!}
                                            {!! trans('langPassCannotChange3') !!} <a href='mailto:{{ $emailhelpdesk }}'>{{ $emailhelpdesk }}</a>
                                            {!! trans('langPassCannotChange4') !!}
                                        </p>
                                    </div>
                                </div>
                                
                            @endif
                            @if($found_editable_password)
                                @if(!$mail_sent)
                                   
                                        <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                            <div class='alert alert-danger'>
                                                <p><strong>{!! trans('langAccountEmailError1') !!}</strong></p>
                                                <p>{!! trans('langAccountEmailError2') !!} {{ $email }}.</p>
                                                <p>{!! trans('langAccountEmailError3') !!} <a href='mailto:{{ $emailhelpdesk }}'>{{ $emailhelpdesk }}</a>.</p>
                                            </div>
                                        </div>
                                    
                                @elseif(!isset($auth))
                                    
                                        <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                            <div class='alert alert-success'>
                                                {!! trans('lang_pass_email_ok') !!} <strong>{!! q($email) !!}</strong>
                                            </div>
                                        </div>
                                    
                                @endif
                            @endif
                        @else
                            @if(isset($res_second_attempt) && $res_second_attempt)
                                
                                    <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                        <div class='alert alert-danger'>
                                            <p>{!! trans('langLostPassPending') !!}</p>
                                        </div>
                                    </div>
                                
                            @else
                                
                                    <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                        <div class='alert alert-danger'>
                                            <p><strong>{{ trans('langAccountNotFound1') }} ({{ "$userName / $email" }})</strong></p>
                                            <p>{{ trans('langAccountNotFound2') }} <a href='mailto:{{ $emailhelpdesk }}'>{{ $emailhelpdesk }}</a>, {{ trans('langAccountNotFound3') }}</p>
                                        </div>
                                    </div>
                                
                            @endif
                        @endif
                    @else
                       
                            <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                <div class='alert alert-info'>{!! trans('lang_pass_intro') !!}</div>
                            </div>
                        
                        
                            <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                <div class='form-wrapper shadow-sm p-3 mt-5 rounded'>
                                    <form class='form-horizontal' role='form' method='post' action='{!! $_SERVER['SCRIPT_NAME'] !!}'>
                                        <div class='row'><div class='col-sm-8'><h4>{!! trans('langUserData') !!}</h4></div></div>
                                        <div class='form-group mt-3'>
                                            <div class='col-sm-12'>
                                                <input class='form-control' type='text' name='userName' id='userName' autocomplete='off' placeholder='{!! trans('lang_username') !!}'>
                                            </div>
                                        </div>
                                        <div class='form-group mt-3'>
                                            <div class='col-sm-12'>
                                                <input class='form-control' type='text' name='email' id='email' autocomplete='off' placeholder='{!! trans('lang_email') !!}'>
                                            </div>
                                        </div>
                                        <div class='form-group mt-3'>
                                            <div class='col-sm-8'>
                                                <button class='btn btn-primary' type='submit' name='send_link' value='{{ $lang_pass_submit }}'>{!! trans('lang_pass_submit') !!}</button>
                                                <button class='btn btn-secondary' href='{{ $urlServer }}'>{!! trans('langCancel') !!}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection



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

<div class="col-12 main-section">
<div class='{{ $container }} main-container'>
        <div class="row m-auto">

                    <div class='col-12 mb-4'>
                        <h1>{{ trans('lang_remind_pass') }}</h1>
                    </div>

                    @if(Session::has('message'))
                    <div class='col-12 all-alerts'>
                        <div class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">
                            @php 
                                $alert_type = '';
                                if(Session::get('alert-class', 'alert-info') == 'alert-success'){
                                    $alert_type = "<i class='fa-solid fa-circle-check fa-lg'></i>";
                                }elseif(Session::get('alert-class', 'alert-info') == 'alert-info'){
                                    $alert_type = "<i class='fa-solid fa-circle-info fa-lg'></i>";
                                }elseif(Session::get('alert-class', 'alert-info') == 'alert-warning'){
                                    $alert_type = "<i class='fa-solid fa-triangle-exclamation fa-lg'></i>";
                                }else{
                                    $alert_type = "<i class='fa-solid fa-circle-xmark fa-lg'></i>";
                                }
                            @endphp
                            
                            @if(is_array(Session::get('message')))
                                @php $messageArray = array(); $messageArray = Session::get('message'); @endphp
                                {!! $alert_type !!}<span>
                                @foreach($messageArray as $message)
                                    {!! $message !!}
                                @endforeach</span>
                            @else
                                {!! $alert_type !!}<span>{!! Session::get('message') !!}</span>
                            @endif
                            
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    @endif

                    

                    @if(isset($_REQUEST['u']) and isset($_REQUEST['h']))
                        @if(isset($is_valid))
                            @if(isset($user_pass_updated))
                                <div class='col-12'>
                                    <div class="alert alert-success"><i class='fa-solid fa-circle-check fa-lg'></i><span><p>{!! trans('langAccountResetSuccess1') !!}</p></span></div>
                                </div>
                            @elseif(isset($user_pass_notupdate))
                                <div class='col-12'>
                                    <div class='alert alert-warning'>
                                    <i class='fa-solid fa-triangle-exclamation fa-lg'></i><span>
                                        {!! implode("\n", $error_messages) !!}</span>
                                    </div>
                                </div>
                            @endif
                            @if(!$change_ok)
                                
                                <div class='col-xl-6 col-lg-8 col-md-8 col-12 ms-auto me-auto'>
                                    <div class='form-wrapper form-edit Borders shadow-sm p-3 wrapper-lostpass'>

                                        <form class="form-horizontal" role="form" method='post' action='{{ $_SERVER['SCRIPT_NAME'] }}'>
                                            <input type='hidden' name='u' value='{{ $userUID }}'>
                                            <input type='hidden' name='h' value='{{ q($_REQUEST['h']) }}'>
                                            <div class="form-group">
                                                <label  class='col-sm-12 control-label-notes'>{!! trans('langNewPass1') !!}</label>
                                                <div class="col-sm-12">
                                                    <input type='password' placeholder="{!! trans('langNewPass1') !!}" class='form-control' size='40' name='newpass' value='' id='password' autocomplete='off'>&nbsp;<span id='result'></span>
                                                </div>
                                            </div>
                                            <div class="form-group mt-4">
                                                <label class="col-sm-12 control-label-notes">{!! trans('langNewPass2') !!}</label>
                                                <div class="col-sm-12">
                                                    <input type='password' placeholder="{!! trans('langNewPass2') !!}" class='form-control' size='40' name='newpass1' value='' autocomplete='off'>
                                                </div>
                                            </div>
                                            <div class='form-group mt-5'>
                                                <div class='col-12 d-flex justify-content-center align-items-center'>
                                                    <input class='btn  submitAdminBtn' type='submit' name='submit' value="{!! trans('langModify') !!}">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class='col-12'>
                                <div class='alert alert-danger'><i class='fa-solid fa-circle-xmark fa-lg'></i><span>{!! trans('langAccountResetInvalidLink') !!}</span></div>
                            </div>
                        @endif

                    @elseif(isset($_POST['send_link']))
                        @if($res_first_attempt)
                            @if(!password_is_editable($res_first_attempt->password))

                                <div class='col-12'>
                                    <div class='alert alert-danger'><i class='fa-solid fa-circle-xmark fa-lg'></i><span>
                                        <p><strong>{!! trans('langPassCannotChange1') !!}</strong></p>
                                        <p>
                                            {!! trans('langPassCannotChange2') !!} {!! get_auth_info($auth) !!}
                                            {!! trans('langPassCannotChange3') !!} <a href='mailto:{{ $emailhelpdesk }}'>{{ $emailhelpdesk }}</a>
                                            {!! trans('langPassCannotChange4') !!}
                                        </p></span>
                                    </div>
                                </div>

                            @endif
                            @if($found_editable_password)
                                @if(!$mail_sent)

                                        <div class='col-12'>
                                            <div class='alert alert-danger'><i class='fa-solid fa-circle-xmark fa-lg'></i><span>
                                                <p><strong>{!! trans('langAccountEmailError1') !!}</strong></p>
                                                <p>{!! trans('langAccountEmailError2') !!} {{ $email }}.</p>
                                                <p>{!! trans('langAccountEmailError3') !!} <a href='mailto:{{ $emailhelpdesk }}'>{{ $emailhelpdesk }}</a>.</p></span>
                                            </div>
                                        </div>

                                @elseif(!isset($auth))

                                        <div class='col-12'>
                                            <div class='alert alert-success'><i class='fa-solid fa-circle-check fa-lg'></i><span>
                                                {!! trans('lang_pass_email_ok') !!} <strong>{!! q($email) !!}</strong></span>
                                            </div>
                                        </div>

                                @endif
                            @endif
                        @else
                            @if(isset($res_second_attempt) && $res_second_attempt)

                                    <div class='col-12'>
                                        <div class='alert alert-danger'><i class='fa-solid fa-circle-xmark fa-lg'></i><span>
                                            <p>{!! trans('langLostPassPending') !!}</p></span>
                                        </div>
                                    </div>

                            @else

                                    <div class='col-12'>
                                        <div class='alert alert-danger'><i class='fa-solid fa-circle-xmark fa-lg'></i><span>
                                            <p><strong>{{ trans('langAccountNotFound1') }} ({{ "$userName / $email" }})</strong></p>
                                            <p>{{ trans('langAccountNotFound2') }} <a href='mailto:{{ $emailhelpdesk }}'>{{ $emailhelpdesk }}</a>, {{ trans('langAccountNotFound3') }}</p></span>
                                        </div>
                                    </div>

                            @endif
                        @endif
                    @else
                        <div class='col-12'>
                            <div class='col-12 mb-5' style='text-align: justify;'>
                                {!! trans('lang_pass_intro') !!}
                            </div>

                            <div class='col-lg-6 col-12 ms-auto me-auto mt-3'>
                                <div class='form-wrapper form-edit Borders shadow-sm p-3 wrapper-lostpass'>
                                    <form class='form-horizontal' role='form' method='post' action='{!! $_SERVER['SCRIPT_NAME'] !!}'>
                                        <div class='row'><div class='col-sm-8'><h4 class='control-label-notes ps-1 mt-1'>{!! trans('langUserData') !!}</h4></div></div>
                                        <div class='form-group'>
                                            <div class='col-sm-12'>
                                                <input class='form-control' type='text' name='userName' id='userName' autocomplete='off' placeholder='{!! trans('lang_username') !!}'>
                                            </div>
                                        </div>
                                        <div class='form-group mt-4'>
                                            <div class='col-sm-12'>
                                                <input class='form-control' type='text' name='email' id='email' autocomplete='off' placeholder='{!! trans('lang_email') !!}'>
                                            </div>
                                        </div>
                                        <div class='form-group mt-5'>
                                            <div class='col-12 d-flex justify-content-center align-items-center'>
                                               
                                                    
                                                        <button class='btn submitAdminBtn' type='submit' name='send_link' value='{{ trans('langSend') }}'>{!! trans('langSend') !!}</button>
                                                    
                                                        <button class='btn cancelAdminBtn ms-1' href='{{ $urlServer }}'>{!! trans('langCancel') !!}</button>
                                                    
                                               
                                                
                                                
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @endif
                
        </div>
    
</div>
</div>

@endsection


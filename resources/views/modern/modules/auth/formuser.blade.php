@extends('layouts.default')

@section('content')

    <div class="col-12 main-section">
        <div class='{{ $container }} main-container'>
            <div class="row m-auto">

                <div class='col-12'>
                    <h3>{!! $toolName !!}</h3>
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

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if (!$eclass_prof_reg)
                    <div class='col-sm-12'>
                        <div class='alert alert-danger'>
                            <i class='fa-solid fa-circle-xmark fa-lg'></i>
                            <span>{{ trans('langForbidden') }}</span>
                        </div>
                    </div>
                @else
                    @if (isset($_POST['submit']))
                        <div class='alert alert-success'>
                            <i class='fa-solid fa-circle-check fa-lg'></i>
                            <span>{{ trans('langRequestSuccess') }}</span>
                        </div>
                        @if ($email_errors)
                            <div class='alert alert-warning'>
                                <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                <span>{{ trans('langMailErrorMessage') }}&nbsp;{{ trans('emailhelpdesk') }}.</span>
                            </div>
                        @endif
                    @else
                        @if ($email_invalid)
                            <div class='alert alert-warning'>
                                <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                <span>{{ trans('langEmailAddressErrors') }}</span>
                            </div>
                        @else
                            <div class='col-12'>
                                <div class='alert alert-info'>
                                    <i class='fa-solid fa-circle-info fa-lg'></i>
                                    <span>{{ trans('langInfoStudReq') }}</span>
                                </div>
                            </div>
                            <div class='col-12 mt-4'>
                                <div class='row row-cols-1 row-cols-lg-2 g-lg-5 g-4'>
                                    <div class='col-lg-6 col-12'>
                                        <div class='form-wrapper form-edit px-0 border-0'>
                                            <form class='form-horizontal' role='form' action='{{ $_SERVER['SCRIPT_NAME'] }}' method='post'>


                                                <div class='col-12'>
                                                    <div class='form-group'>
                                                        <label for='ProfComments' class='col-sm-12 control-label-notes'>{{ trans('langComments') }}</label>
                                                        <div class='col-sm-12'>
                                                            <textarea id='ProfComments' class='form-control' name='usercomment' cols='30' rows='4' placeholder='{{ trans('langReasonsForCreatingCourses') }}...'>{!! q($usercomment) !!}</textarea>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class='col-12'>
                                                    <div class='form-group mt-4'>
                                                        <label for='UserPhone' class='col-sm-12 control-label-notes'>{{ trans('langPhone') }}</label>
                                                        <div class='col-sm-12'>
                                                            <input id='UserPhone' class='form-control' type='text' name='userphone' value='' size='15' maxlength='15' placeholder='{{ trans('langCompulsory') }}'>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class='col-12'>
                                                    <div class='form-group mt-4'>
                                                        <label for='dialog-set-value' class='col-sm-12 control-label-notes'>{{ trans('langFaculty') }}</label>
                                                        <div class='col-sm-12'>
                                                            {!! $buildusernode !!}
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class='col-12 d-flex justify-content-end mt-4'>
                                                    <div class='form-group'>
                                                        <input class='btn submitAdminBtn secodandary-submit' type='submit' name='submit' value='{{ trans('langSubmitNew') }}'>
                                                    </div>
                                                </div>


                                            </form>
                                        </div>
                                    </div>
                                    <div class='col-lg-6 col-12 d-none d-lg-block'>
                                        <img class='form-image-modules' src='{!! get_registration_form_image() !!}' alt='Request'>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('layouts.default')

@push('head_scripts')
    <script type='text/javascript' src='{{ $urlAppend }}js/pwstrength.js'></script>
    @if(isset($mail_notification))
        <script type="text/javascript">$(control_deactivate);</script>
    @endif
@endpush

@section('content')

<div class="pb-lg-3 pt-lg-3 pb-0 pt-0">

    <div class="container-fluid main-container">

        <div class="row rowMedium">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active col_maincontent_active_ProfileUser">
                    
                <div class="row p-lg-5 p-md-5 ps-1 pe-1 pt-5 pb-5">

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                    @include('layouts.partials.legend_view',['is_editor' => $is_editor, 'course_code' => $course_code])

                    {!! $action_bar !!}

                    <form action='{{ $_SERVER['SCRIPT_NAME'] }}' method='post'>
                        @if(isset($mailNotVerified))
                            <div class='alert alert-warning'>
                                {{ trans('langMailNotVerified') }}
                                <a href = '{{ $urlAppend }}modules/auth/mail_verify_change.php?from_profile=true'>{{ trans('langHere') }}</a>
                            </div>
                        @endif
                        @if(isset($mail_notification))
                            <div class='alert alert-info'>{{ trans('langEmailUnsubscribeWarning') }}</div>
                            <input type='checkbox' id='unsub' name='unsub' value='1'>&nbsp;{{ trans('langEmailFromCourses') }}
                        @endif
                        <div class='alert alert-info'>{!! trans('langInfoUnsubscribe') !!}</div>

                        <div class='row'>
                            <div class='col-lg-6 col-12 d-none d-md-none d-lg-block'>
                                <div class='col-12 h-100 left-form'></div>
                            </div>
                            <div class='col-lg-6 col-12'>
                                <div class='form-wrapper form-edit p-3 rounded'>
                                    <div id='unsubscontrols'>
                                    @if(isset($_REQUEST['cid']))
                                    <div class='col-12 mb-3 label d-inline-flex align-items-top'>
                                        <input type='checkbox' name='c_unsub' value='1' {{ $selected }}>&nbsp;{{ $course_title }}<br />
                                        <input type='hidden' name='cid' value='{{ getIndirectReference($cid) }}'>
                                    </div>
                                    @else
                                        @foreach($_SESSION['courses'] as $code => $status)
                                            @if (course_status(course_code_to_id($code)) != COURSE_INACTIVE)
                                            <div class='col-12 mb-3 label d-inline-flex align-items-top'>
                                                <input type='checkbox' name='c_unsub[{{ $code }}]' value='1' {{ get_user_email_notification($uid, course_code_to_id($code)) ? 'checked' : '' }}>&nbsp;{{ course_code_to_title($code) }}<br>
                                            </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    </div>
                                    <br>
                                    <div class='row'>
                                        <div class='col-6'>
                                            <input class='btn btn-sm btn-primary submitAdminBtn w-100' type='submit' name='submit' value='{{ trans('langSubmit') }}'>
                                        </div>
                                        <div class='col-6'>
                                             <a class='btn btn-sm btn-secondary cancelAdminBtn w-100' href='display_profile.php'>{{ trans('langCancel') }}</a>
                                        </div>
                                    </div>
                                    
                                   
                                </div>
                            </div>
                        </div>
                        {!! generate_csrf_token_form_field() !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
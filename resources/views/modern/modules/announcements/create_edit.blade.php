
@extends('layouts.default')

@section('content')


<!-- <script type="text/javascript" src="{{ $urlAppend }}template/modern/js/my_courses_color_header.js"></script> -->
<!-- <script src ="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>   -->

<script>
    $(function () {
        var langEmptyGroupName = '{{ js_escape(trans('langEmptyAnTitle')) }}';

        $('input[name=startdate_active]').prop('checked') ? $('input[name=startdate_active]').parents('.input-group').children('input').prop('disabled', false) : $('input[type=checkbox]').eq(0).parents('.input-group').children('input').prop('disabled', true);
        $('input[name=enddate_active]').prop('checked') ? $('input[name=enddate_active]').parents('.input-group').children('input').prop('disabled', false) : $('input[name=enddate_active]').parents('.input-group').children('input').prop('disabled', true);

        $('input[name=startdate_active]').on('click', function() {
            if ($('input[name=startdate_active]').prop('checked')) {
                $('input[name=enddate_active]').prop('disabled', false);
            } else {
                $('input[name=enddate_active]').prop('disabled', true);
                $('input[name=enddate_active]').prop('checked', false);
                $('input[name=enddate_active]').parents('.input-group').children('input').prop('disabled', true);
            }
        });

        $('.input-group-addon input[type=checkbox]').on('click', function(){
            var prop = $(this).parents('.input-group').children('input').prop('disabled');
            if(prop){
                $(this).parents('.input-group').children('input').prop('disabled', false);
            } else {
                $(this).parents('.input-group').children('input').prop('disabled', true);
            }
        });

        $('#select-recipients').select2();
        $('#selectAll').click(function(e) {
            e.preventDefault();
            var stringVal = [];
            $('#select-recipients').find('option').each(function(){
                stringVal.push($(this).val());
            });
            $('#select-recipients').val(stringVal).trigger('change');
        });
        $('#removeAll').click(function(e) {
            e.preventDefault();
            var stringVal = [];
            $('#select-recipients').val(stringVal).trigger('change');
        });

        $('#startdate, #enddate').datetimepicker({
            format: 'dd-mm-yyyy hh:ii',
            pickerPosition: 'bottom-right',
            language: '{{ $language }}',
            autoclose: true
        });

    });
</script>


    <div class="pb-3 pt-3">

        <div class="container-fluid main-container">

            <div class="row">

                <div class="col-xl-2 col-lg-2 col-md-0 col-sm-0 col-0 justify-content-center col_sidebar_active"> 
                    <div class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block">
                        @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                    </div>
                </div>

                <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active">
                   
                    <div class="row p-5">

                                <nav class="navbar navbar-expand-lg navrbar_menu_btn">
                                    <button type="button" id="menu-btn" class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block btn btn-primary menu_btn_button">
                                        <i class="fas fa-align-left"></i>
                                        <span></span>
                                    </button>
                                    
                                
                                    <a class="btn btn-primary d-lg-none mr-auto" type="button" data-bs-toggle="offcanvas" href="#collapseTools" role="button" aria-controls="collapseTools" style="margin-top:-10px;">
                                        <i class="fas fa-tools"></i>
                                    </a>
                                </nav>

                                <nav class="navbar_breadcrumb" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ $urlAppend }}main/portfolio.php">{{trans('langPortfolio')}}</a></li>
                                        <li class="breadcrumb-item"><a href="{{ $urlAppend }}main/my_courses.php">{{trans('mycourses')}}</a></li>
                                        <li class="breadcrumb-item"><a href="{{$urlServer}}courses/{{$course_code}}/index.php">{{$currentCourseName}}</a></li>
                                        <li class="breadcrumb-item"><a href="{{ $urlAppend }}modules/announcements/index.php?course={{$course_code}}">{{trans('langAnnouncements')}}</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">{{trans('langAddAnn')}}</li>
                                    </ol>
                                </nav>


                                <div class="offcanvas offcanvas-start d-lg-none mr-auto" tabindex="-1" id="collapseTools" aria-labelledby="offcanvasExampleLabel">
                                    <div class="offcanvas-header">
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                                    </div>
                                </div>
                        
                            
                                <form class="form-horizontal" role="form" method="post" action="{{$urlAppend}}modules/announcements/submit.php?course={{$course_code}}">
                                        
                                       <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class="row p-2"></div><div class="row p-2"></div>
                                            <legend class="float-none w-auto py-2 px-4 notes-legend"><span class="pos_TitleCourse"><i class="fas fa-bell"></i> {{$toolName}} <<strong >{{$currentCourseName}} <small>({{$course_code}})</small></strong>></span>
                                                <div class="manage-course-tools"style="float:right">
                                                    @if($is_editor)
                                                        @include('layouts.partials.manageCourse',[$urlAppend => $urlAppend,'coursePrivateCode' => $course_code])
                                                    @endif
                                                </div>
                                            </legend>
                                        </div>

                                        <div class="row p-2"></div><div class="row p-2"></div>
                                        <span class="control-label-notes ms-1">{{trans('langTeacher')}}: <small>{{course_id_to_prof($course_id)}}</small></span>
                                        <div class="row p-2"></div><div class="row p-2"></div>


                                        <div class="form-group {{ $antitle_error }}">
                                            <label for="AnnTitle" class="col-sm-4 control-label-notes">{{ trans('langAnnTitle') }}:</label>
                                            <div class="col-sm-12 ">
                                                <input class="form-control" type="text" name="antitle" value="{{ $titleToModify }}"/>
                                                <span class='help-block'>{{ Session::getError('antitle') }}</span>
                                            </div>
                                        </div>

                                        <div class="row p-2"></div>

                                        <div class='form-group'>
                                            <label for='AnnBody' class='col-sm-4 control-label-notes'>{{ trans('langAnnBody') }}:</label>
                                            <div class='col-sm-12'>{!! $contentToModify !!}</div>
                                        </div>

                                        <div class="row p-2"></div>

                                        <div class='form-group'><label for='Email' class='col-sm-offset-2 col-sm-12 control-label-notes'>{{ trans('langEmailOption') }}:</label></div>
                                        <div class='form-group'>
                                            <div class='col-sm-offset-2 col-sm-12'>
                                                <select class='form-control' name='recipients[]' multiple='multiple' id='select-recipients'>
                                                    <option value='-1' selected>{{ trans('langAllUsers') }}</option>
                                                    @foreach ($course_users as $cu)
                                                        <option value='{{ $cu->user_id }}'>{{$cu->name}} ({{$cu->email}})</option>
                                                    @endforeach
                                                </select>
                                                <a href='#' id='selectAll'>{{ trans('langJQCheckAll') }}</a> | <a href='#' id='removeAll'>{{ trans('langJQUncheckAll') }}</a>
                                            </div>
                                        </div>

                                        <div class="row p-2"></div>
                                        {!! $tags !!}
                                        <div class="row p-2"></div>

                                        <!-- <div class='form-group'>
                                            <label for='Email' class='col-sm-offset-2 col-sm-12 control-panel'>{{ trans('langAnnouncementActivePeriod') }}:</label>
                                        </div>

                                        <div class="row p-2"></div> -->

                                        <div class='form-group {{ $startdate_error }}'>
                                            <label for='startdate' class='col-sm-4 control-label-notes'>{{ trans('langStartDate') }} :</label>
                                            <div class='col-sm-12'>
                                                <div class='input-group'> 
                                                    <span class='input-group-addon'>
                                                        <input type='checkbox' name='startdate_active' {{ $start_checkbox }}>
                                                    </span>
                                                    <input class='form-control' name='startdate' id='startdate' type='text' value = '{{ $showFrom }}'>
                                                </div>
                                                <span class='help-block'>{{ $startdate_error }}</span>
                                            </div>
                                        </div>

                                        <div class="row p-2"></div>

                                        <div class='form-group {{ $enddate_error }}'>
                                            <label for='enddate' class='col-sm-4 control-label-notes'>{{ trans('langEndDate') }} :</label>
                                            <div class='col-sm-12'>
                                                <div class='input-group'>
                                                    <span class='input-group-addon'>
                                                        <input type='checkbox' name='enddate_active' {{ $end_checkbox}} {{ $end_disabled}}>
                                                    </span>
                                                    <input class='form-control' name='enddate' id='enddate' type='text' value = '{{ $showUntil }}'>
                                                </div>
                                                <span class='help-block'>{{ $enddate_error }}</span>
                                            </div>
                                        </div>

                                        <div class="row p-2"></div>

                                        <div class='form-group'>
                                            <div class='col-sm-12 col-sm-offset-2'>
                                                <div class='checkbox'>
                                                    <label>
                                                        <input type='checkbox' name='show_public' {{  $checked_public }}> {{ trans('langViewShow') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row p-2"></div>

                                        <div class='form-group'>

                                            <input type='hidden' name='id' value='{{ $announce_id }}'>
                                            <input type='hidden' name='course' value='{{ $course_code }}'>
                                            <input type='hidden' name='editorFromCreateEditAnnouncement' value='{{$is_editor}}'>
                                            
                                            <div class='col-sm-offset-2 col-sm-12'>
                                                <button type="submit" class="btn btn-primary" name="submitAnnouncement" value="{{ trans('langAdd') }}">{{ trans('langSubmit') }}</button>
                                                <a href="{{ $_SERVER['SCRIPT_NAME'] }}?course={{ $course_code }}" class="btn btn-secondary">{{ trans('langCancel') }}</a>
                                            </div>
                                            
                                        </div>
                                        
                                    
                                </form>
                            
                        
                    </div> 
                    
                </div>
            </div>
        </div>
    </div>

@endsection


    

@extends('layouts.default')

@push('head_scripts')
    <script type="text/javascript">
        var langEmptyGroupName = '{{ trans('langNoPgTitle') }}'
    </script>
@endpush

@section('content')

<div class="pb-3 pt-3">

    <div class="container-fluid main-container">

        <div class="row">

            <div class="col-xl-2 col-lg-2 col-md-0 col-sm-0 col-0 justify-content-center col_sidebar_active"> 
                <div class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block">
                    @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                </div>
            </div>

            <div class="col-lg-10 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active">
                    
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
                            <li class="breadcrumb-item active" aria-current="page">{{$toolName}}</li>
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


                    <div class="col-xxl-12 col-lx-12 col-lg-12 col-md-10 col-sm-6">
                        <legend class="float-none w-auto py-2 px-4 notes-legend"><span class="pos_TitleCourse"><i class="fas fa-folder-open" aria-hidden="true"></i> {{$toolName}} {{trans('langsOfCourse')}} <<strong>{{$currentCourseName}} <small>({{$course_code}})</small></strong>></span>
                            <div class="float-end manage-course-tools">
                                @if($is_editor)
                                    @include('layouts.partials.manageCourse',[$urlAppend => $urlAppend,'coursePrivateCode' => $course_code])              
                                @endif
                            </div>
                        </legend>
                    </div>
                    <div class="row p-2"></div>
                    <small>{{trans('langTeacher')}}: {{course_id_to_prof($course_id)}}</small>
                    <div class="row p-2"></div>

                    <div class="panel panel-default panel-action-btn-default">
                        <div class='panel-heading notes_thead'>
                            <h3 class='panel-title text-white'>{{ trans('langActivateCourseTools') }}</h3>
                        </div>
                        <form name="courseTools" action="{{ $_SERVER['SCRIPT_NAME'] }}?course={{ $course_code }}" method="post" enctype="multipart/form-data">
                            <div class="table-responsive">
                                <table class="announcements_table">
                                    <tr>
                                    <th width="45%" class="text-center">{{ trans('langInactiveTools') }}</th>
                                    <th width="10%" class="text-center">{{ trans('langMove') }}</th>
                                    <th width="45%" class="text-center">{{ trans('langActiveTools') }}</th>
                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <select class="form-control" name="toolStatInactive[]" id='inactive_box' size='17' multiple>
                                                @foreach($toolSelection[0] as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <button type="button" class="btn btn-secondary" onClick="move('inactive_box','active_box')"><span class="fa fa-arrow-right"></span></button><br><br>
                                            <button type="button" class="btn btn-secondary" onClick="move('active_box','inactive_box')"><span class="fa fa-arrow-left"></span></button>
                                        </td>
                                        <td class="text-center">
                                            <select class="form-control" name="toolStatActive[]" id='active_box' size='17' multiple>
                                                @foreach($toolSelection[1] as $item)
                                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-center">
                                            <input type="submit" class="btn btn-primary" value="{{ trans('langSubmit') }}" name="toolStatus" onClick="selectAll('active_box',true)" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            {!! $csrf !!}
                        </form>
                    </div>

                    <div class='panel panel-default panel-action-btn-default'>
                        <div class='pull-right pt-1 pb-2 pe-3'>
                            <div id='operations_container'>
                                <a class='btn btn-success' href='{{ $_SERVER['SCRIPT_NAME'] }}?course={{ $course_code }}&amp;action=true'><span class='fa fa-plus-circle'></span> {{ trans('langAddExtLink') }}</a>
                            </div>
                        </div>
                        <div class='panel-heading notes_thead'>
                            <h3 class='panel-title text-white'> {{ trans('langOperations') }}</h3>
                        </div>
                        <table class='announcements_table'>
                        @foreach($q as $externalLinks)
                            <tr>
                                <td class='text-left'>
                                    <div style='display:inline-block; width: 80%;'>
                                        <strong>{{  $externalLinks->title }}</strong>
                                        <div style='padding-top:8px;'><small class='text-muted'>{{ $externalLinks->url }}</small></div>
                                    </div>
                                    <div class='pull-right'>
                                        <a class='text-danger' href='?course={{ $course_code }}&amp;delete={{ getIndirectReference($externalLinks->id) }}'><span class='fa fa-times'></span></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </div>

                    <div class='panel panel-default panel-action-btn-default'>
                        <div class='panel-heading notes_thead'>
                            <span class='panel-title text-white' style='line-height: 45px;'>{{ trans('langLtiConsumer') }}</span>
                            <span class='pull-right pt-1 pe-3'>
                            <a class='btn btn-success' href='../lti_consumer/index.php?course={{ $course_code }}&amp;add=1'>
                            <span class='fa fa-plus-circle'></span>{{ trans('langNewLTITool') }}</a>
                        </div>
                    </div>

                    {!! lti_app_details() !!}
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
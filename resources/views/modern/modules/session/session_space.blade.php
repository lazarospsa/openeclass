@extends('layouts.default')

@section('content')


<div class="col-12 main-section">
    <div class='{{ $container }} module-container py-lg-0'>
        <div class="course-wrapper d-lg-flex align-items-lg-strech w-100">

            <div id="background-cheat-leftnav" class="col_sidebar_active d-flex justify-content-start align-items-strech ps-lg-0 pe-lg-0"> 
                <div class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block ContentLeftNav">
                    @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                </div>
            </div>

            <div class="col_maincontent_active">
                    
                <div class="row">

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="collapseTools">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                        </div>
                    </div>

                    @include('layouts.partials.legend_view')
                    
                    @if(!$is_editor)
                        {!! $action_bar !!}
                    @endif

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

                    <div class='col-12'>
                        @if($is_editor)
                            {!! action_bar(array(
                                    array(
                                        'title' => trans('langBack'),
                                        'url' => $urlAppend . 'modules/session/index.php?course=' . $course_code,
                                        'icon' => 'fa-reply',
                                        'button-class' => 'btn-success',
                                        'level' => 'primary-label'
                                    ),
                                    array('title' => trans('langEditUnitSection'),
                                        'url' => $urlAppend . 'modules/session/edit.php?course=' . $course_code . '&session=' . $session_id,
                                        'icon' => 'fa fa-edit',
                                        'level' => 'primary-label',
                                        'button-class' => 'btn-success'),
                                    array('title' => trans('langCompleteSession'),
                                        'url' => $urlAppend . 'modules/session/complete.php?course=' . $course_code . '&session=' . $session_id,
                                        'icon' => 'fa fa-gear',
                                        'button-class' => 'btn-success'),
                                    array('title' => trans('langSelect') . ' ' . trans('langInsertDoc'),
                                        'url' => $urlAppend . 'modules/session/session_space.php?course=' . $course_code . '&session=' . $session_id . '&type=doc',
                                        'icon' => 'fa fa-folder',
                                        'level' => 'secondary',
                                        'show' => !is_module_disable(MODULE_ID_DOCS)),
                                    array('title' => trans('langSelect') . ' ' . trans('langInsertWork'),
                                        'url' => $urlAppend . 'modules/session/session_space.php?course=' . $course_code . '&session=' . $session_id . '&type=work',
                                        'icon' => 'fa fa-upload',
                                        'level' => 'secondary',
                                        'show' => !is_module_disable(MODULE_ID_ASSIGN)),
                                    array('title' => trans('langSelect') . ' ' . trans('langInsertTcMeeting'),
                                        'url' => $urlAppend . 'modules/session/session_space.php?course=' . $course_code . '&session=' . $session_id . '&type=tc',
                                        'icon' => 'fa fa-exchange',
                                        'level' => 'secondary',
                                        'show' => (!is_module_disable(MODULE_ID_TC) && is_enabled_tc_server($course_id)))
                                    ))
                            !!}

                            <div class='mt-4'>

                            @if(count($all_session) > 0)
                                <div class='col-12'>
                                    <div class="card panelCard card-units px-lg-4 py-lg-3 p-3">
                                        <div class='card-body p-0'>
                                            <ul class="tree-units">
                                                <li>
                                                    <details open>
                                                        <summary><h3 class='mb-0'>{{ trans('langSession')}}</h3></summary>
                                                        <ul>
                                                            @foreach ($all_session as $cu)
                                                                <li>
                                                                    <a class='TextBold @if($is_consultant && ($cu->finish < $current_time or !$cu->visible)) opacity-help @endif'
                                                                        href='{{ $urlServer }}modules/session/session_space.php?course={{ $course_code }}&amp;session={{ $cu->id }}'>
                                                                        {{ $cu->title }}
                                                                    </a>
                                                                    <br>
                                                                    @if (!is_null($cu->start))
                                                                        <small>
                                                                            <span class='help-block @if($is_consultant && ($cu->finish < $current_time or !$cu->visible)) opacity-help @endif'>
                                                                                {{ trans('langStart')}}:&nbsp;{!! format_locale_date(strtotime($cu->start), 'short', false) !!} &nbsp;&nbsp; -- &nbsp;&nbsp;
                                                                                {{ trans('langEnd')}}:&nbsp;{!! format_locale_date(strtotime($cu->finish), 'short', false) !!} </br>
                                                                            </span>
                                                                        </small>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </details>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            </div>
                        @endif
                    </div>

                    @if($is_editor)
                        <div class='col-12 mt-4'>
                            {!! $type_resource !!}
                        </div>
                    @endif

                    <div class='col-12 mt-4'>
                        <div class="card panelCard px-lg-4 py-lg-3">
                            <div class='card-header border-0 d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                <h3>{{ $pageName }}</h3>
                               {{-- 
                                @if($course_start_week or $course_finish_week)
                                    <div>
                                        <small>{{ $course_start_week }}&nbsp;{{ $course_finish_week }}</small>
                                    </div>
                                @endif 
                                --}}
                            </div>
                            <div class="card-body">
                                {{-- 
                                    <div>
                                        {!! $comments !!}
                                    </div> 
                                --}}
                                <div class='unit-resources mt-3'>
                                    {!! $tool_content_sessions !!}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    
    </div>
</div>


@endsection
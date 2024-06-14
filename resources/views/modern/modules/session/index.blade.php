@extends('layouts.default')

@push('head_scripts')
    <script type='text/javascript'>
        $(document).ready(function() {
            $('#table_sessions').DataTable({
                'sPaginationType': 'full_numbers',
                'bAutoWidth': true,
                'searchDelay': 1000,
                'aoColumns': [
                    {'bSortable' : false },
                    {'bSortable' : false },
                    {'bSortable' : false },
                    {'bSortable' : false },
                ],
                'order' : [],
                'oLanguage': {
                    'sLengthMenu': '{{ trans('langDisplay') }} _MENU_ {{ trans('langResults2') }}',
                    'sZeroRecords': '{{ trans('langNoResult') }}',
                    'sInfo': '{{ trans('langDisplayed') }} _START_ {{ trans('langTill') }} _END_ {{ trans('langFrom2') }} _TOTAL_ {{ trans('langTotalResults') }}',
                    'sInfoEmpty': '{{ trans('langDisplayed') }} 0 {{ trans('langTill') }} 0 {{ trans('langFrom2') }} 0 {{ trans('langResults2') }}',
                    'sInfoFiltered': '',
                    'sInfoPostFix': '',
                    'sSearch': '',
                    'sUrl': '',
                    'oPaginate': {
                        'sFirst': '&laquo;',
                        'sPrevious': '&lsaquo;',
                        'sNext': '&rsaquo;',
                        'sLast': '&raquo;'
                    }
                }
            });
            $('.dataTables_filter input').attr({
                class: 'form-control input-sm ms-0 mb-3',
                placeholder: '{{ trans('langSearch') }}...'
            });
        });

    </script>

    <!-- About deletion -->
    <script>
        $(function() {
            $(document).on('click', '.delete-session', function(e){
                var sessionID = $(this).attr('data-id');
                document.getElementById("deleteSession").value = sessionID;
            });
            $(document).on('click', '.leave-session', function(e){
                var sessionLeaveID = $(this).attr('data-id');
                document.getElementById("leaveSession").value = sessionLeaveID;
            });
        });
    </script>
@endpush

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
                    
                    {!! $action_bar !!}

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
                        @if(count($individuals_group_sessions) > 0)

                        {{--
                            <table class='table-default' id='table_sessions'>
                                <thead>
                                    <tr>
                                        <th class='px-2'>{{ trans('langTitle') }}</th>
                                        <th class='px-2'>{{ trans('langStart') }}</th>
                                        <th class='px-2'>{{ trans('langFinish') }}</th>
                                        <th class='px-2'></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($individuals_group_sessions as $s)
                                        <tr>
                                            <td class='@if($s->finish < $current_time or !$s->visible) opacity-help @endif'>
                                                <a class="link-color @if(!$is_tutor_course && !$is_consultant && $s->display == 'not_visible') pe-none opacity-help @endif" 
                                                    href='{{ $urlAppend }}modules/session/session_space.php?course={{ $course_code }}&session={{ $s->id }}'>
                                                    {{ $s->title }}
                                                </a>
                                            </td>
                                            <td class='@if($s->finish < $current_time or !$s->visible) opacity-help @endif'>
                                                {{ format_locale_date(strtotime($s->start), 'short') }}
                                            </td>
                                            <td class='@if($s->finish < $current_time or !$s->visible) opacity-help @endif'>
                                                {{ format_locale_date(strtotime($s->finish), 'short') }}
                                            </td>
                                            <td class='text-end'>
                                                {!! action_button(array(
                                                    array('title' => trans('langMoreCourseInfo'),
                                                            'url' => "#",
                                                            'icon-class' => "more-info-session",
                                                            'icon-extra' => "data-id='{$s->id}' data-bs-toggle='modal' data-bs-target='#MoreSessionInfo{$s->id}'",
                                                            'icon' => 'fa-solid fa-circle-info'),
                                                    array('title' => trans('langEdit'),
                                                            'url' => $urlAppend . "modules/session/edit.php?course=" . $course_code . "&session=" . $s->id,
                                                            'icon-class' => "edit-session",
                                                            'icon' => 'fa-edit',
                                                            'show' => ($is_tutor_course or $is_consultant)),
                                                    array('title' => trans('langDelete'),
                                                            'url' => "#",
                                                            'icon-class' => "delete-session",
                                                            'icon-extra' => "data-id='{$s->id}' data-bs-toggle='modal' data-bs-target='#SessionDelete'",
                                                            'icon' => 'fa-xmark',
                                                            'show' => ($is_tutor_course or $is_consultant))
                                                    )
                                                ) !!}
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="MoreSessionInfo{{ $s->id }}" tabindex="-1" aria-labelledby="MoreSessionInfoLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md modal-success">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <div class='modal-title'>
                                                            <div class='icon-modal-default'><i class='fa-solid fa-circle-info fa-xl Neutral-500-cl'></i></div>
                                                            <h3 class='modal-title-default text-center mb-0 mt-2' id="MoreSessionInfoLabel">{!! trans('langMoreCourseInfo') !!}</h3>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <ul class='list-group lisy-group-flush'>
                                                            <li class='list-group-item element d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <p>{{ trans('langSSession') }}</p>
                                                                <p>
                                                                    @if($s->type=='one')
                                                                        {{ trans('langIndividualS') }}
                                                                    @else
                                                                        {{ trans('langGroupS') }}
                                                                    @endif
                                                                </p>
                                                            </li>
                                                            <li class='list-group-item element d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <p>{{ trans('langTypeRemote') }}</p>
                                                                <p>
                                                                    @if($s->type_remote)
                                                                        {{ trans('langRemote') }}
                                                                    @else
                                                                        {{ trans('langNotRemote') }}
                                                                    @endif
                                                                </p>
                                                            </li>
                                                            <li class='list-group-item element d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <p>{{ trans('langStatement') }}</p>
                                                                <p>
                                                                    @if($s->start < $current_time && $current_time < $s->finish)
                                                                        {{ trans('langInProgress') }}
                                                                    @elseif($current_time < $s->start)
                                                                        {{ trans('langSessionHasNotStarted') }}
                                                                    @else
                                                                        <span class='text-danger TextBold'>{{ trans('langSessionHasExpired') }}</span>
                                                                    @endif
                                                                </p>
                                                            </li>
                                                            <li class='list-group-item element d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <p>{{ trans('langVisible') }}</p>
                                                                <p>
                                                                    @if(!$s->visible)
                                                                        {{ trans('langNo')}}
                                                                    @else
                                                                        {{ trans('langYes')}}
                                                                    @endif
                                                                </p>
                                                            </li>
                                                            @if(!$is_tutor_course && !$is_consultant)
                                                            <li class='list-group-item element d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <p>{{ trans('langCompletedSession') }}</p>
                                                                <p>
                                                                    {!! $s->icon !!}
                                                                </p>
                                                            </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                    <div class="modal-footer d-flex justify-content-center align-items-center">
                                                        <a class="btn cancelAdminBtn" href="" data-bs-dismiss="modal">{{ trans('langClose') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>

                        --}}



                            <div class='row row-cols-1 m-auto'>
                                @foreach($individuals_group_sessions as $s)
                                    <div class='col px-0 mb-4'>
                                        <div class='card panelCard border-card-left-default px-lg-4 py-lg-3'>
                                            <div class='card-header border-0 d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                <h3>{{ $s->title }}</h3>
                                                <div>
                                                    {!! action_button(array(
                                                        array('title' => trans('langEdit'),
                                                                'url' => $urlAppend . "modules/session/edit.php?course=" . $course_code . "&session=" . $s->id,
                                                                'icon-class' => "edit-session",
                                                                'icon' => 'fa-edit',
                                                                'show' => ($is_tutor_course or $is_consultant)),
                                                        array('title' => trans('langDelete'),
                                                                'url' => "#",
                                                                'icon-class' => "delete-session",
                                                                'icon-extra' => "data-id='{$s->id}' data-bs-toggle='modal' data-bs-target='#SessionDelete'",
                                                                'icon' => 'fa-xmark',
                                                                'show' => ($is_tutor_course or $is_consultant))
                                                        )
                                                    ) !!}
                                                </div>
                                            </div>
                                            <div class='card-body'>
                                                <div class='d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                    <div>
                                                        <ul class='list-group list-group-flush'>
                                                            <li class='list-group-item list-group-item-action secondary d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <div>{{ trans('langStart') }}:</div>
                                                                <div class='secondary-text'>{{ format_locale_date(strtotime($s->start), 'short') }}</div>
                                                            </li>
                                                            <li class='list-group-item list-group-item-action secondary d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <div>{{ trans('langFinish') }}:</div>
                                                                <div class='secondary-text'>{{ format_locale_date(strtotime($s->finish), 'short') }}</div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <ul class='list-group list-group-flush'>
                                                            <li class='list-group-item list-group-item-action secondary d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <div>{{ trans('langSSession') }}:</div>
                                                                <div class='secondary-text'>
                                                                    @if($s->type=='one')
                                                                        {{ trans('langIndividualS') }}
                                                                    @else
                                                                        {{ trans('langGroupS') }}
                                                                    @endif
                                                                </div>
                                                            </li>
                                                            <li class='list-group-item list-group-item-action secondary d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <div>{{ trans('langTypeRemote') }}:</div>
                                                                <div class='secondary-text'>
                                                                    @if($s->type_remote)
                                                                        {{ trans('langRemote') }}
                                                                    @else
                                                                        {{ trans('langNotRemote') }}
                                                                    @endif
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <ul class='list-group list-group-flush'>
                                                            <li class='list-group-item list-group-item-action secondary d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <div>{{ trans('langStatement') }}:</div>
                                                                <div class='secondary-text'>
                                                                    @if($s->start < $current_time && $current_time < $s->finish)
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <div class="spinner-grow text-success" role="status" style='width:20px; height:20px;'>
                                                                                <span class="visually-hidden">Loading...</span>
                                                                            </div>
                                                                            {{ trans('langInProgress') }}
                                                                        </div>
                                                                    @elseif($current_time < $s->start)
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <div class="spinner-border text-warning" role="status" style='width:20px; height:20px;'>
                                                                                <span class="visually-hidden">Loading...</span>
                                                                            </div>
                                                                            {{ trans('langSessionHasNotStarted') }}
                                                                        </div>
                                                                        
                                                                    @else
                                                                        <span class='text-danger TextBold'>{{ trans('langSessionHasExpired') }}</span>
                                                                    @endif
                                                                </div>
                                                            </li>
                                                            <li class='list-group-item list-group-item-action secondary d-flex justify-content-between align-items-center gap-3 flex-wrap'>
                                                                <div>{{ trans('langVisible') }}:</div>
                                                                <div class='secondary-text'>
                                                                    @if(!$s->visible)
                                                                        {{ trans('langNo')}}
                                                                    @else
                                                                        {{ trans('langYes')}}
                                                                    @endif
                                                                </div>
                                                            </li>
    
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class='d-flex justify-content-between align-items-center gap-5 flex-wrap mt-4'>
                                                    
                                                    <div class='flex-fill'>
                                                        <h5>{{ trans('langPercentageSessionCompletion') }}:</h5>
                                                        @if($s->has_badge > 0)
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar" style="width: {{ $s->percentage }}%;" aria-valuenow="{{ $s->percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $s->percentage }}%</div>
                                                            </div>
                                                        @elseif($s->has_badge == -1)
                                                            <div class='secondary-text'>{{ trans('langSessionNotStarted') }}</div>
                                                        @else
                                                            <div class='secondary-text'>{{ trans('langSessionHasNotBadge') }}</div>
                                                        @endif
                                                    </div>
                                                    
                                                    <a class="btn successAdminBtn 
                                                                @if(!$is_tutor_course && !$is_consultant && $s->display == 'not_visible') pe-none opacity-help @endif" 
                                                        href='{{ $urlAppend }}modules/session/session_space.php?course={{ $course_code }}&session={{ $s->id }}'>
                                                        {{ trans('langEnter') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>










                        @else
                            <div class='alert alert-warning'>
                                <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                <span>{{ trans('langNoInfoAvailable') }}</span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    
    </div>
</div>



<div class='modal fade' id='SessionDelete' tabindex='-1' aria-labelledby='SessionDeleteLabel' aria-hidden='true'>
    <form method='post' action="{{ $_SERVER['SCRIPT_NAME'] }}?course={{ $course_code }}">
        <div class='modal-dialog modal-md'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <div class='modal-title'>
                        <div class='icon-modal-default'><i class='fa-regular fa-trash-can fa-xl Accent-200-cl'></i></div>
                        <h3 class="modal-title-default text-center mb-0 mt-2" id="SessionDeleteLabel">{!! trans('langDelete') !!}</h3>
                    </div>
                </div>
                <div class='modal-body text-center'>
                    {{ trans('langContinueToDelSession') }}
                    <input id="deleteSession" type='hidden' name='session_id'>
                </div>
                <div class='modal-footer d-flex justify-content-center align-items-center'>
                    <a class="btn cancelAdminBtn" href="" data-bs-dismiss="modal">{{ trans('langCancel') }}</a>
                    <button type='submit' class="btn deleteAdminBtn" name="delete_session">
                        {{ trans('langDelete') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class='modal fade' id='SessionLeave' tabindex='-1' aria-labelledby='SessionLeaveLabel' aria-hidden='true'>
    <form method='post' action="{{ $_SERVER['SCRIPT_NAME'] }}?course={{ $course_code }}">
        <div class='modal-dialog modal-md'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <div class='modal-title'>
                        <div class='icon-modal-default'><i class='fa-regular fa-trash-can fa-xl Accent-200-cl'></i></div>
                        <h3 class="modal-title-default text-center mb-0 mt-2" id="SessionLeaveLabel">{!! trans('langLeaveSession') !!}</h3>
                    </div>
                </div>
                <div class='modal-body text-center'>
                    {{ trans('langContinueToLeaveSession') }}
                    <input id="leaveSession" type='hidden' name='session_leave_id'>
                </div>
                <div class='modal-footer d-flex justify-content-center align-items-center'>
                    <a class="btn cancelAdminBtn" href="" data-bs-dismiss="modal">{{ trans('langCancel') }}</a>
                    <button type='submit' class="btn deleteAdminBtn" name="leave_session">
                        {{ trans('langDelete') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

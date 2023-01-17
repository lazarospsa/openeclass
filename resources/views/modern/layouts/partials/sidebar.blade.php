
<div id="leftnav" class="col-12 sidebar float-menu pt-3">


    {{-- @if(!isset($dont_display_array_in_sidebar) or $dont_display_array_in_sidebar != 1)
        {!! $course_home_sidebar_widgets !!}
    @endif --}}


    @php $is_course_teacher = check_editor($uid,$course_id); @endphp

    @if(($is_editor or $is_power_user or $is_departmentmanage_user or $is_usermanage_user or $is_course_teacher) && $course_code)
        <p class="text-center text-light @if($course_home_sidebar_widgets) mt-4 @else mt-3 @endif viewPageAs">{{ trans('langViewAs') }}:</p>

        <!-- THIS IS FIRST CHOICE OF VIEW-STUDENT-TEACHER TOOGLE-BUTTON -->
        {{--<form method="post" action="{{ $urlAppend }}main/student_view.php?course={{ $course_code }}" id="student-view-form" class='d-flex justify-content-center'>
            <button class='btn-toggle{{ !$is_editor ? " btn-toggle-on" : "" }} w-100' data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ $is_editor ? trans('langStudentViewEnable') : trans('langStudentViewDisable')}}">
                <span class="on">{{ trans('langCStudent2') }}</span>
                <span class="off">{{ trans('langCTeacher') }}</span>
                <p class="on2">{{ trans('langCStudent2') }}</p>
                <p class="off2">{{ trans('langCTeacher') }}</p>
            </button>
        </form>--}}

         <!-- THIS IS SECOND CHOICE OF VIEW-STUDENT-TEACHER TOOGLE-BUTTON -->
        <form method="post" action="{{ $urlAppend }}main/student_view.php?course={{ $course_code }}" id="student-view-form" class='d-flex justify-content-center mb-5'>
            <label class="switch-sidebar">
                <input class="form-check-input slider-btn-on btn-toggle{{ !$is_editor ? " btn-toggle-on" : "" }}" type="checkbox" id="flexSwitchCheckChecked" {{ !$is_editor ? "checked" : "" }}>
                <div class="slider-round">
                    <span class="on">{{ trans('langCStudent2') }}</span>
                    <span class="off">{{ trans('langCTeacher') }}</span>
                </div>
            </label>
        </form>
    @endif

    <div class="panel-group accordion mt-4" id="sidebar-accordion">
        <div class="panel">
            @foreach ($toolArr as $key => $tool_group)
                <a id="Tool{{$key}}" class="collapsed parent-menu mt-5" data-bs-toggle="collapse" href="#collapse{{ $key }}">
                    <div class="panel-sidebar-heading">
                        <div class="panel-title h3">
                            <div class='d-inline-flex align-items-top'>
                                <span class="fa fa-chevron-right tool-sidebar"></span>
                                <span class='text-wrap tool-sidebar-text ps-2 text-uppercase'>{{ $tool_group[0]['text'] }}</span>
                            </div>
                        </div><hr class='text-white'>
                    </div>
                </a>
                <div id="collapse{{ $key }}" class="panel-collapse list-group accordion-collapse collapse {{ $tool_group[0]['class'] }}{{ $key == $default_open_group? ' show': '' }} Borders Collapse{{ $key }}" aria-labelledby="Tool{{$key}}" data-bs-parent="#sidebar-accordion">
                    @foreach ($tool_group[1] as $key2 => $tool)
                        <a href="{!! $tool_group[2][$key2] !!}" class='leftMenuToolCourse list-group-item {{ module_path($tool_group[2][$key2]) == $current_module_dir ? " active" : ""}} Borders border-0' {{ is_external_link($tool_group[2][$key2]) || $tool_group[3][$key2] == 'fa-external-link' ? ' target="_blank"' : "" }}>
                            <div class='d-inline-flex align-items-top'>
                                <span class="fa {{ $tool_group[3][$key2] }} fa-fw posTool tool-sidebar toolSidebarTxt pe-2"></span>
                                <span class='toolSidebarTxt'>{!! $tool !!}</span>
                            </div>

                        </a>
                    @endforeach
                </div>
                <div class='p-3'></div>
            @endforeach
        </div>
        {{ isset($eclass_leftnav_extras) ? $eclass_leftnav_extras : "" }}
    </div>
</div>
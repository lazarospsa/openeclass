
    <div class="col-xl-12 col-lg-12 col-md-12 col-12 col-12 justify-content-center col_maincontent_active_Homepage">

        <div class="row p-lg-5 p-md-5 ps-1 pe-1 pt-5 pb-5">

            @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

            <div class="col-12 mt-3">
                <div class='pb-3 ps-3 pe-0 legendViewContent text-center'>
                    <span class='courseInfoText TextExtraBold blackBlueText'>{{ trans('langMyCourses') }}</span>
                </div>
            </div>
           
            @if(Session::has('message'))
            <div class='col-12 all-alerts'>
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

            <div class='col-12 mt-4 d-flex justify-content-start'>
                <a class='btn submitAdminBtn' href="{{ $urlAppend }}modules/auth/courses.php">
                    <span class='fa fa-check'></span>{{ trans('langRegCourses') }}
                </a>
            </div>

            <div class="col-12 mt-3">
                <div class="table-responsive">
                    <table id="courses_table_pag" class="table-default">
                        <thead class="list-header">
                            <tr>
                                <th>{{ trans('langTitle') }}</th>
                                <th>{{ trans('langCode') }}</th>
                                <th>{{ trans('langTeacher') }}</th>
                                <th>{{ trans('langType') }}</th>
                                <th class='text-center'>{{ trans('langOptions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($myCourses)
                        <div class="row cources-bars-page" id="cources-bars-page-1">
                                @foreach ($myCourses as $course)
                                <tr>
                                    <td>
                                        <a class='@if($course->visible == 3) InvisibleCourse @endif' href="{{$urlServer}}courses/{{$course->code}}/index.php">{{ q($course->title) }}</a>
                                    </td>
                                    <td><span class="text-secondary @if($course->visible == 3) InvisibleCourse @endif">({{ q($course->public_code) }})</span></td>
                                    <td><span class="text-secondary @if($course->visible == 3) InvisibleCourse @endif">{{ q($course->professor) }}</span></td>
                                    <td class='text-center'>
                                        @if($course->visible == 1) 
                                            <button type="button" class="btn btn-transparent" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{trans('langRegCourse')}}">
                                              <span class='fa fa-lock text-secondary'></span>
                                            </button>
                                        @endif
                                        @if($course->visible == 2) 
                                            <button type="button" class="btn btn-transparent" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{trans('langOpenCourse')}}">
                                              <span class='fa fa-unlock text-success'></span>
                                            </button>
                                        @endif
                                        @if($course->visible == 0) 
                                            <button type="button" class="btn btn-transparent" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{trans('langClosedCourse')}}">
                                              <span class='fa fa-lock orangeText'></span>
                                            </button>
                                        @endif
                                        @if($course->visible == 3) 
                                            <button type="button" class="btn btn-transparent" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{trans('langInactiveCourse')}}">
                                              <span class='fa fa-exclamation-triangle text-danger InvisibleCourse'></span>
                                            </button>
                                        @endif
                                    </td>
                                    <td class='text-center'>
                                        <!-- check if uid is editor of course or student -->
                                        <!------------------------------------------------->
                                        @php $is_course_teacher = check_editor($uid,$course->course_id); @endphp 
                                        <!------------------------------------------------->
                                        <!------------------------------------------------->
                                        @if($_SESSION['status'] == USER_TEACHER and $is_course_teacher and $course->status == 1)
                                        <a href="{{$urlServer}}modules/course_info/index.php?from_home=true&amp;course={{$course->code}}">
                                            <span class="fa fa-wrench" title="" data-bs-original-title="{{trans('langAdm')}}" data-bs-toggle="tooltip" data-bs-placement="bottom">
                                            </span><span class="sr-only">{{trans('langAdm')}}</span>
                                        </a>
                                        @else
                                        <button class='btn deleteAdminBtn m-auto d-block' data-bs-toggle="modal" data-bs-target="#exampleModal{{$course->course_id}}" >
                                            <i class="fa fa-times"></i>
                                        </button>
                                        @endif

                                    </td>
                                </tr>

                                
                                <div class="modal fade" id="exampleModal{{$course->course_id}}" tabindex="-1" aria-labelledby="exampleModalLabel{{$course->course_id}}" aria-hidden="true">
                                    <form method="post" action="{{$urlAppend}}main/unregcours.php?u={{ $_SESSION['uid'] }}&amp;cid={{$course->course_id}}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel{{$course->course_id}}">{{ trans('langUnCourse') }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ trans('langConfirmUnregCours') }}<strong class="text-capitalize orangeText"> {{$course->title}}</strong>;
                                                    <input type='hidden' name='fromMyCoursesPage' value="1">
                                                </div>
                                                <div class="modal-footer">
                                                    <a class="btn cancelAdminBtn" href="" data-bs-dismiss="modal">{{trans('langCancel')}}</a>

                                                    <button type='submit' class="btn deleteAdminBtn" name="doit">{{trans('langDelete')}}</a>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            @endforeach
                        </div>   
                        @else
                            <div class='col-12'>
                                <div class='alert alert-warning'>{{ trans('langNoCourses') }}</div>
                            </div> 
                        @endif

                            
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


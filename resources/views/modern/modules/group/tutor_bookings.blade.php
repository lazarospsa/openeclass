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

                    @if($is_editor)
                        {!! isset($action_bar) ?  $action_bar : '' !!}
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


                    @if($is_course_admin or $is_tutor)
                        @if(count($bookings) > 0)
                            <div class='col-12'>
                                <div class="row row-cols-1 row-cols-md-2 g-4">
                                    @foreach($bookings as $b)
                                        <div class='col'>
                                            <div class="card panelCard h-100 px-lg-4 py-lg-3">
                                                
                                                <div class='card-header border-0 d-flex justify-content-between align-items-center'>
                                                    <h3>{{ $b->title }}</h3>
                                                </div>
                                                
                                                <div class="card-body">
                                                    <p><span class='TextBold'>{{ trans('langFrom') }}:</span>&nbsp;{{ format_locale_date(strtotime($b->start), 'short') }}</p>
                                                    <p><span class='TextBold'>{{ trans('langUntil') }}:</span>&nbsp;{{ format_locale_date(strtotime($b->end), 'short') }}</p>
                                                    <p><span class='TextBold'>{{ trans('langAccept') }}:</span>&nbsp;
                                                        @if($b->accepted == 1)
                                                            {{ trans('langYes')}}
                                                        @else
                                                            {{ trans('langNo')}}
                                                        @endif
                                                    </p>
                                                </div> 
                                                
                                                <div class='card-footer border-0 d-flex justify-content-start align-items-center gap-2 flex-wrap'>
                                                    
                                                        @if($b->accepted == 0)
                                                            <div class='col-12'>
                                                                <div class='alert alert-warning'>
                                                                    <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                                                    <span> {!!trans('langNoAcceptanceOfBooking') !!}</span>
                                                                </div>
                                                            </div>
                                                            <button class='btn submitAdminBtnDefault' data-bs-toggle='modal' data-bs-target='#BookingAccept{{ $b->id }}'>
                                                                {!! trans('langAcceptBooking') !!}
                                                            </button>
                                                        @else
                                                        <div class='col-12'>
                                                            <div class='alert alert-info'>
                                                                <i class='fa-solid fa-circle-info fa-lg'></i>
                                                                <span> {!!trans('langHasAcceptedBookingAndContinue') !!}</span>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        
                                                        <button class='btn deleteAdminBtn' data-bs-toggle='modal' data-bs-target='#BookingDelete{{ $b->id }}'>
                                                            {!! trans('langCancel') !!}
                                                        </button>
                                                        
                                                    
                                                    <!-- FOR MEMBER -->
                                                     {{--   
                                                        @if($b->accepted == 1)
                                                            <div class='col-12 d-flex justify-content-center align-items-center flex-wrap gap-2'>
                                                                <span class='badge badge-success'><i class='fa fa-check'></i></span>
                                                                {{ trans('langHasAcceptedBooking') }}
                                                            </div>
                                                        @else
                                                            <p class='text-center'>{!! trans('langHasNoAcceptedBookingYet') !!}</p>
                                                        @endif
                                                        <div class='col-12 d-flex justify-content-center align-items-center flex-wrap gap-2 mt-2'>
                                                            <a class='btn btn-danger' href='{{ $urlAppend }}modules/mentoring/programs/group/bookingsMentee/booking_space.php?group_id={!! getInDirectReference($b->group_id) !!}&mentor_id={!! getInDirectReference($b->mentor_id) !!}'>
                                                                {{ trans('langCancelBooking') }}
                                                            </a>
                                                        </div>
                                                    --}}
                                                    <!-- FOR MEMBER -->
                                                        
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="BookingAccept{{ $b->id }}" tabindex="-1" aria-labelledby="BookingAcceptLabel{{ $b->id }}" aria-hidden="true">
                                            <form method="post" action="{{ $_SERVER['SCRIPT_NAME'] }}?course={{ $course_code }}&amp;group_id={{ $b->group_id }}&amp;bookings_of_tutor={{ $b->tutor_id }}">
                                                <div class="modal-dialog modal-md modal-success">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <div class='modal-title'>
                                                                <div class='icon-modal-default'><i class='fa-solid fa-cloud-arrow-up fa-xl Neutral-500-cl'></i></div>
                                                                <h3 class='modal-title-default text-center mb-0' id="BookingAcceptLabel{{ $b->id }}">{!! trans('langAcceptBooking') !!}</h3>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            {{ trans('langContinueToBooking') }}
                                                            <input type='hidden' name='accept_booking_id' value="{{ $b->booking_id }}">
                                                        </div>
                                                        <div class="modal-footer d-flex justify-content-center align-items-center">
                                                            <a class="btn cancelAdminBtn" href="" data-bs-dismiss="modal">{{ trans('langCancel') }}</a>
                                                            <button type='submit' class="btn submitAdminBtnDefault" name="accept_booking">
                                                                {{ trans('langAcceptBooking') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class='modal fade' id='BookingDelete{{ $b->id }}' tabindex='-1' aria-labelledby='BookingDeleteLabel{{ $b->id }}' aria-hidden='true'>
                                            <form method='post' action="{{ $_SERVER['SCRIPT_NAME'] }}?course={{ $course_code }}&amp;group_id={{ $b->group_id }}&amp;bookings_of_tutor={{ $b->tutor_id }}">
                                                <div class='modal-dialog modal-md'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <div class='modal-title' id='BookingDeleteLabel{{ $b->id }}'>
                                                                <div class='icon-modal-default'><i class='fa-regular fa-trash-can fa-xl Accent-200-cl'></i></div>
                                                                <h3 class="modal-title-default text-center mb-0" id="BookingDeleteLabel{{ $b->id }}">{!! trans('langCancelBooking') !!}</h3>
                                                            </div>
                                                        </div>
                                                        <div class='modal-body text-center'>
                                                            {{ trans('langContinueToBooking') }}
                                                            <input type='hidden' name='booking_id' value="{{ $b->booking_id }}">
                                                        </div>
                                                        <div class='modal-footer d-flex justify-content-center align-items-center'>
                                                            <a class="btn cancelAdminBtn" href="" data-bs-dismiss="modal">{{ trans('langCancel') }}</a>
                                                            <button type='submit' class="btn deleteAdminBtn" name="delete_booking">
                                                                {{ trans('langDelete') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class='col-12'>
                                <div class='alert alert-warning'>
                                    <i class='fa-solid fa-triangle-exclamation fa-lg'></i><span>{{ trans('langNoInfoAvailable') }}</span>
                                </div>
                            </div>                          
                        @endif
                    @endif



                </div>
            </div>
        </div>
    </div>
</div>




@endsection
@extends('layouts.default')

@section('content')

<div class="col-12 main-section">
    <div class='{{ $container }} main-container'>
        <div class="row m-auto">
                @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                @include('layouts.partials.legend_view')

                @if(isset($action_bar))
                    {!! $action_bar !!}
                @else
                    <div class='mt-4'></div>
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

                @if (!$tc_cron_running)
                    @include('admin.other.extapps.bbb.bbb_cron_modal')
                @endif

                @if (count($q) > 0)
                    <div class='col-12'>
                        <div class='table-responsive'>
                            <table class='table-default'>
                                <thead>
                                <tr class='list-header'>
                                    <th class = 'text-center'>{{ trans('langName') }}</th>
                                    <th>{{ trans('langBBBEnabled') }}</th>
                                    <th>{{ trans('langUsers') }}</th>
                                    <th>{{ trans('langActiveRooms') }}</th>
                                    <th>{{ trans('langBBBMIcs') }} / {{ trans('langBBBCameras') }}</th>
                                    <th>{{ trans('langBBBServerOrderP') }} / {{ trans('langBBBServerLoad') }}</th>
                                    <th>{!! icon('fa-gears') !!}</th>
                                </tr>
                                </thead>

                            {!! $bbb_cnt !!}

                        </table></div>
                    </div>
                @else
                    <div class='col-12'>
                       <div class='alert alert-warning'><i class='fa-solid fa-triangle-exclamation fa-lg'></i><span>{{ trans('langNoAvailableBBBServers') }}</span></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('layouts.default')

@section('content')

<div class="pb-lg-3 pt-lg-3 pb-0 pt-0">

    <div class="container-fluid main-container">

        <div class="row rowMedium">

            <div class="col-12 justify-content-center col_maincontent_active_Homepage">
                    
                <div class="row p-lg-5 p-md-5 ps-1 pe-1 pt-5 pb-5">

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                    @include('layouts.partials.legend_view',['is_editor' => $is_editor, 'course_code' => $course_code])

                    {!! isset($action_bar) ?  $action_bar : '' !!}

                    @if($announcementsID)
                       
                        <div class='col-sm-12'>
                            <div class="panel panel-default">
                                <div class="panel-heading">                   
                                    <div class='panel-title'>{{$announcementsID->title}}</div>
                                </div>
                                <div class="panel-body">
                                    <span class="text-secondary">
                                        {!! $announcementsID->body !!}
                                    </span>
                                </div>
                                <div class='panel-footer'>
                                    <div class='text-dark fw-bold text-center'>{{trans('langDate')}}: <span class='info-date fw-normal'>{{ $announcementsID->date }}</span></div>
                                </div>
                            </div>
                        </div>
                       
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.default')

@section('content')

<div class="col-12 main-section">
<div class='{{ $container }}'>
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
                            
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                    @endif

                    
                    @if (isset($auth_methods_active) == 0)
                        <div class='col-12'><div class='alert alert-warning'><i class='fa-solid fa-triangle-exclamation fa-lg'></i><span>{{ trans('langAuthChangeno') }}</span></div></div>
                    @else
                    
                    <div class='col-lg-6 col-12'>
                        <div class='form-wrapper form-edit rounded'>
                            <form class='form-horizontal' role='form' name='authchange' method='post' action='{{ $_SERVER['SCRIPT_NAME'] }}'>   
                            <fieldset>
                                <div class='form-group'>
                                    <label class='col-sm-12 control-label-notes'>{{ trans('langAuthChangeto') }}</label>
                                    <div class='col-sm-12'>
                                        {!! selection($auth_methods_active, 'auth_change', '', "class='form-control'") !!}
                                    </div>
                                </div>
                                <input type='hidden' name='auth' value='{{ getIndirectReference(intval($auth)) }}'>  
                                <div class='col-12 mt-5 d-flex justify-content-center align-items-center'>
                                    <input class='btn submitAdminBtn' type='submit' name='submit' value='{{ trans('langModify') }}'>
                                </div>
                            </fieldset>
                            {!! generate_csrf_token_form_field() !!}    
                            </form>
                        </div>
                    </div>
                    <div class='col-lg-6 col-12 d-none d-md-none d-lg-block'>
                        <div class='col-12 h-100 left-form'></div>
                    </div>
                    @endif    
                
        </div>
</div>

</div>     
@endsection
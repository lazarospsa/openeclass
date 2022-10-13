@extends('layouts.default')

@section('content')


<div class="pb-lg-3 pt-lg-3 pb-0 pt-0">

    <div class="container-fluid main-container">

        <div class="row rowMedium">

            <div id="background-cheat-leftnav" class="col-xl-2 col-lg-3 col-md-0 col-sm-0 col-0 justify-content-center col_sidebar_active"> 
                <div class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block">
                    @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                </div>
            </div>

            <div class="col-xl-10 col-lg-9 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active">
                    
                <div class="row p-lg-5 p-md-5 ps-1 pe-1 pt-5 pb-5">

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])


                    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="collapseTools" aria-labelledby="offcanvasExampleLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                        </div>
                    </div>


                    @include('layouts.partials.legend_view',['is_editor' => $is_editor, 'course_code' => $course_code])
                    

                    @if(Session::has('message'))
                    <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 all-alerts'>
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
                    
                    {!! isset($action_bar) ?  $action_bar : '' !!}

                    <div class='col-12'>
                        <div class='form-wrapper shadow-sm p-3 rounded'>
                            
                            <form class = 'form-horizontal' role='form' method='post' action='index.php?course={{ $course_code }}&urlview={{ $urlview }}'>
                                @if ($action == 'editcategory')
                                    <input type='hidden' name='id' value='{{ getIndirectReference($id) }}'>
                                @endif
                                <fieldset>

                                    <div class="form-group{{ $categoryNameError ? ' has-error' : ''}} mt-3">
                                    <label for='CatName' class='col-sm-6 control-label-notes'>{{ trans('langCategoryName') }}:</label>
                                    <div class='col-sm-12'>
                                        <input class='form-control' type='text' name='categoryname' size='53' placeholder='{{ trans('langCategoryName') }}' value='{{ isset($category) ? $category->name : "" }}'>
                                        {!! Session::getError('categoryname', "<span class='help-block'>:message</span>") !!}
                                    </div>
                                    </div>

                                  

                                    <div class='form-group mt-3'>
                                        <label for='CatDesc' class='col-sm-6 control-label-notes'>{{ trans('langDescription') }}:</label>
                                        <div class='col-sm-12'>
                                            <textarea class='form-control' rows='5' name='description'>{{ isset($category) ? $category->description : "" }}</textarea>
                                        </div>
                                    </div>

                                  
                                    
                                    <div class='form-group mt-5'>
                                        <div class='col-12'>
                                            <div class='row'>
                                                <div class='col-6'>
                                                    <input type='submit' class='btn btn-primary btn-sm submitAdminBtn w-100' name='submitCategory' value="{{ $form_legend }}">
                                                </div>
                                                <div class='col-6'>
                                                    <a href='index.php?course={{ $course_code }}' class='btn btn-secondary btn-sm cancelAdminBtn w-100'>{{ trans('langCancel') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                {!! generate_csrf_token_form_field() !!}
                            </form>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</div>
@endsection
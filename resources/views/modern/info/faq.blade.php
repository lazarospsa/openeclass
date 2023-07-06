@extends('layouts.default')

@section('content')

<div class="col-12 basic-section p-xl-5 px-lg-3 py-lg-5">

        <div class="row rowMargin">

            <div class="col-12 col_maincontent_active_Homepage">
                
                <div class="row">

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                    @include('layouts.partials.legend_view',['is_editor' => $is_editor, 'course_code' => $course_code])
                    
                    @if (count($faqs) > 0)
                        <div class='col-12'>
                            {!! $action_bar !!}
                        </div> 
                    @endif
                   
                    <div class='col-12'>
                        <div class='panel'>
                            <div class='panel-group faq-section' id='accordion' role='tablist' aria-multiselectable='true'>
                                @if (count($faqs) == 0)
                                    <div class='alert alert-warning'>
                                    <i class='fa-solid fa-triangle-exclamation fa-lg'></i><span>
                                        {{ trans('langFaqNoEntries') }}</span>
                                    </div>
                                @else


                                    <ul class="list-group list-group-flush">
                                        @foreach ($faqs as $key => $faq)
                                        
                                                <li class="list-group-item border-0 Shadow-cols p-3 mb-4">
                                                    <a class='d-flex align-items-start control-label-notes' role='button' data-bs-toggle='collapse' href='#faq-{{ $faq->id }}' aria-expanded='true' aria-controls='#{{ $faq->id }}'>
                                                        <span class='pe-2'>{{ $key+1 }}.</span>
                                                        <span>{!! $faq->title !!}</span>
                                                        
                                                    </a>

                                                    <div id='faq-{{ $faq->id }}' class='panel-collapse accordion-collapse collapse border-0 bg-light rounded-0' role='tabpanel' aria-labelledby='heading{{ $faq->id }}' data-bs-parent='#accordion'>
                                                        <div class='panel-body px-5'>
                                                            {!! $faq->body !!}
                                                        </div>
                                                    </div>
                                                </li>
                                               
                                                
                                        @endforeach
                                    </ul>

                                @endif
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
   
</div>

@endsection

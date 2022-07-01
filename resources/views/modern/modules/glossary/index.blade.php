<?php 
    $lesson = Database::get()->querySingle("SELECT * FROM `course` WHERE `course`.`title`='{$title_course}' ");
    $course_code_title = $lesson->code;  
    $course_Teacher = $lesson->prof_names;
?>
@extends('layouts.default')

@section('content')

    <div class="pb-3 pt-3">

        <div class="container-fluid main-container">

            <div class="row">

                <div class="col-xl-2 col-lg-2 col-md-0 col-sm-0 col-0 justify-content-center col_sidebar_active"> 
                    <div class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block">
                        @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                    </div>
                </div>

                <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active">
                    
                        <div class="row p-5">

                                    <nav class="navbar navbar-expand-lg navrbar_menu_btn">
                                        <button type="button" id="menu-btn" class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block btn btn-primary menu_btn_button">
                                            <i class="fas fa-align-left"></i>
                                            <span></span>
                                        </button>
                                        
                                    
                                        <a class="btn btn-primary d-lg-none mr-auto" type="button" data-bs-toggle="offcanvas" href="#collapseTools" role="button" aria-controls="collapseTools" style="margin-top:-10px;">
                                            <i class="fas fa-tools"></i>
                                        </a>
                                    </nav>

                                    <nav class="navbar_breadcrumb" aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ $urlAppend }}main/portfolio.php">{{trans('langPortfolio')}}</a></li>
                                            <li class="breadcrumb-item"><a href="{{ $urlAppend }}main/my_courses.php">{{trans('mycourses')}}</a></li>
                                            <li class="breadcrumb-item"><a href="{{$urlServer}}courses/{{$course_code}}/index.php">{{$currentCourseName}}</a></li>
                                            <li class="breadcrumb-item"><a href="{{ $urlAppend }}modules/glossary/index.php?course={{$course_code}}">{{trans('langGlossary')}}</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">{{trans('langGlossaryTerms')}}</li>
                                        </ol>
                                    </nav>


                                    <div class="offcanvas offcanvas-start d-lg-none mr-auto" tabindex="-1" id="collapseTools" aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header">
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                                        </div>
                                    </div>


                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="row p-2"></div><div class="row p-2"></div>
                                        <legend class="float-none w-auto py-2 px-4 notes-legend"><span class="pos_TitleCourse"><i class="fas fa-list" aria-hidden="true"></i> {{$toolName}} {{trans('langsOfCourse')}} <<strong>{{$currentCourseName}} <small>({{$course_code}})</small></strong>></span>
                                            <div class="manage-course-tools"style="float:right">
                                                @if($is_editor)
                                                    
                                                        @include('layouts.partials.manageCourse',[$urlAppend => $urlAppend,'coursePrivateCode' => $course_code])
                                                    
                                                @endif
                                            </div>
                                        </legend>
                                    </div>

                                    <div class="row p-2"></div><div class="row p-2"></div>
                                    <span class="control-label-notes ms-1">{{trans('langTeacher')}}: <small>{{course_id_to_prof($course_id)}}</small></span>
                                    <div class="row p-2"></div><div class="row p-2"></div>

                                    @if($is_editor)
                                        
                                            <!-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                                                <div class="row">
                                                    <div class="col-xxl-10 col-xl-9 col-lg-9 col-md-12 col-sm-12 col-xs-12">
                                                        <button class="btn btn-success glossary_btn1"><i class="fas fa-plus" aria-hidden="true"></i><a class="glossary_a" href="categories.php?course={{$course_code}}&add=1">Προσθήκη κατηγορίας</a></button>
                                                        <button class="btn btn-success glossary_btn2" style="margin-left:-10px"><i class="fas fa-plus" aria-hidden="true"></i><a class="glossary_a" href="index.php?course={{$course_code}}&add=1">Προσθήκη όρου</a></button>
                                                        <button class="btn btn-secondary glossary_btn3" style="margin-left:-9px"><i class="fas fa-list-alt" aria-hidden="true"></i><a class="glossary_a" href="categories.php?course={{$course_code}}">Κατηγορίες</a></button>
                                                    </div>
                                                    
                                        
                                                </div>
                                            </div> -->
                                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 bg-white">{!! isset($action_bar) ?  $action_bar : '' !!}</div>
                                            <div class="row p-2"></div>
                                        
                                    @endif

                                    

                                    @if ($is_editor == 1 && $expand_glossary && $total_glossary_terms > $max_glossary_terms)
                                        <div class='alert alert-warning'>{!! trans('langGlossaryOverLimit',["<b>$max_glossary_terms</b>"]) !!}</div>
                                    @endif   

                                    @if ($glossary_index && count($prefixes) > 1)
                                    <div class="row p-2"></div>
                                    <nav>
                                        
                                        <ul class="pagination">
                                            <small>{{trans('langGlossaryIndex')}}:</small>
                                        @foreach ($prefixes as $key => $letter)
                                            <li {!! (!isset($_GET['prefix']) && !$cat_id && !$key) ||
                                                    (isset($_GET['prefix']) && $_GET['prefix'] == $letter)? " class='active'" : "" !!} ><div class="paging_a_glossary"><a class="paging_a_glossary1" href="{{ $base_url."&amp;prefix=" . urlencode($letter)  }}&editor={{$is_editor}}">{{ $letter }}</a></div></li>
                                        @endforeach
                                        </ul>
                                    </nav>
                                    
                                    @endif

                                
                                    @if ($glossary_terms)
                                    
                                        <div class='table-responsive glossary-categories' style="">
                                            <table class='table' id="glossary_table" style="overflow: inherit">
                                            
                                                <thead class="notes_thead text-light">
                                                    <tr>
                                                        <th scope="col"><span class="notes_th_comment">#</span></th>
                                                        <th scope="col"><span class="notes_th_comment">{{ trans('langGlossaryTerm') }}</span></th>
                                                        <th scope="col"><span class="notes_th_comment">{{ trans('langGlossaryDefinition') }}</span></th>
                                                        <th scope="col"><span class="notes_th_comment">{{ trans('langCategory') }}</span></th>
                                                        <th scope="col"><span class="notes_th_comment">URL</span></th>
                                                        <th scope="col"><span class="notes_th_comment">{{ trans("langComments") }}</span></th>
                                                        @if($is_editor == 1)
                                                            
                                                                <th scope="col"><span class="notes_th_comment"><i class='fas fa-cogs'></i></span></th>
                                                            
                                                        @endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=0; ?>
                                                    @foreach ($glossary_terms as $glossary_term)
                                                    <?php $tmp_edit_id = Database::get()->querySingle("SELECT * FROM `glossary` WHERE `glossary`.`term`='{$glossary_term->term}' ");?>
                                                    <?php $edit_id = $tmp_edit_id->id; ?>
                                                    <?php $i++; ?>
                                                    <tr>
                                                            <th scope="row">{{$i}}</th>
                                                            <td>
                                                                <a href='{{ $base_url."&amp;id=" . getIndirectReference($glossary_term->id) }}'>
                                                                    <strong>{{$glossary_term->term}}</strong>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <em>
                                                                    {{$glossary_term->definition}}
                                                                </em>
                                                            </td>
                                                            <td>
                                                                @if ($glossary_term->category_id)
                                                                
                                                                    
                                                                    <a href='{{ $base_url }}&amp;cat={{ getIndirectReference($glossary_term->category_id) }}'> 
                                                                        {{ $categories[$glossary_term->category_id] }}
                                                                    </a>
                                                                
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($glossary_term->url)
                                                                    <!-- <div>
                                                                    
                                                                                <a href='{{ $glossary_term->url }}' target='_blank'>
                                                                                    {{ $glossary_term->url }}&nbsp;&nbsp;<i class='fas fa-external-link' style='color:#444;'></i>
                                                                                </a>
                                                                        
                                                                    </div>       -->
                                                                    
                                                                    

                                                                    <a class="content-truncate-announcement" data-bs-toggle="modal" role="button" aria-expanded="false" data-bs-target="#ModalUrl{{$i}}">
                                                                        <?php $content_myann = strip_tags($myann->content); ?>
                                                                        <span class="d-inline-block text-truncate" style="max-width: 180px;"><i class="fas fa-arrow-down"></i>{{ $glossary_term->url }}</span>
                                                                    </a>

                                                                    
                                                                    <div class="modal fade modalAnnouncement" id="ModalUrl{{$i}}" tabindex="-1" aria-labelledby="ModalUrl{{$i}}" aria-hidden="true">
                                                                        <div class="modal-dialog modal-xl">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <a href='{{ $glossary_term->url }}' target='_blank'>
                                                                                        {{ $glossary_term->url }}&nbsp;&nbsp;<i class='fas fa-external-link' style='color:#444;'></i>
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($glossary_term->notes)
                                                                    
                                                                        {!! standard_text_escape($glossary_term->notes) !!}
                                                                    
                                                                @endif
                                                            </td>
                                                            @if($is_editor)
                                                            <td>
                                                       
                                                                {!! 
                                                                    action_button(array(
                                                                        array('title' => trans('langEditChange'),
                                                                            'url' => $base_url ."&amp;edit=". getIndirectReference($glossary_term->id),
                                                                            'icon' => 'fa-edit'),
                                                                        array('title' => trans('langDelete'),
                                                                            'url' => $base_url ."&amp;delete=". getIndirectReference($glossary_term->id),
                                                                            'icon' => 'fa-times',
                                                                            'class' => 'delete',
                                                                            'confirm' => trans('langConfirmDelete'))
                                                                        )
                                                                    ) 
                                                                !!}                          
                                                                   
                                                    

                                                            </td>
                                                            @endif
                                                        
                                                    </tr>
                                                
                                                        
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class='xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'><div class='alert alert-warning'>{{trans('langAnalyticsNotAvailable')}} {{trans('langGlossary')}}.</div></div>
                                    @endif
                                   
                                    
                            
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
@endsection
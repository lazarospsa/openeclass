
@extends($is_in_tinymce ? 'layouts.embed' : 'layouts.default')

@section('content')

<?php load_js('tinymce.popup.urlgrabber.min.js');?>

<div class="pb-3 pt-3">

    <div class="container-fluid main-container">

        <div class="row">

            <div id="background-cheat-leftnav" class="col-xl-2 col-lg-2 col-md-0 col-sm-0 col-0 justify-content-center col_sidebar_active">
                <div class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block">
                    @if($course_code)
                        @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                    @else
                        @include('layouts.partials.sidebarAdmin')
                    @endif
                </div>
            </div>

            <div class="col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 justify-content-center col_maincontent_active">

                <div class="row p-lg-5 p-md-5 ps-1 pe-2 pt-5 pb-5">
                    <nav class="navbar navbar-expand-lg navrbar_menu_btn">
                        <button type="button" id="menu-btn" class="d-none d-sm-block d-sm-none d-md-block d-md-none d-lg-block btn btn-primary menu_btn_button">
                            <i class="fas fa-align-left"></i>
                            <span></span>
                        </button>
                        <a class="btn btn-primary d-lg-none mr-auto" type="button" data-bs-toggle="offcanvas" href="#collapseTools" role="button" aria-controls="collapseTools" style="margin-top:-10px;">
                            <i class="fas fa-tools"></i>
                        </a>
                    </nav>

                    @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

                    

                    <div class="offcanvas offcanvas-start d-lg-none mr-auto" tabindex="-1" id="collapseTools" aria-labelledby="offcanvasExampleLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                        @if($course_code)
                            @include('layouts.partials.sidebar',['is_editor' => $is_editor])
                        @else
                            @include('layouts.partials.sidebarAdmin')
                        @endif
                        </div>
                    </div>

                     
                    @include('layouts.partials.legend_view',['is_editor' => $is_editor, 'course_code' => $course_code])

                    {!! $actionBar !!}

                    @if(Session::has('message'))
                    <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-5'>
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }} alert-dismissible fade show" role="alert">
                            {{ Session::get('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </p>
                    </div>
                    @endif

                    
                    <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                        @if ($dialogBox)
                            @include("modules.document.$dialogBox")
                        @endif
                    </div>
                    
                    @if (count($fileInfo) or $curDirName)
                        
                            <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3'>
                                <div class='panel'>
                                    <div class='panel-body'>
                                        @if ($curDirName)
                                            <div class='pull-right'>
                                                <a href='{{$parentLink}}' type='button' class='btn btn-success'>
                                                    <span class='fa fa-level-up'></span>&nbsp;{{ trans('langUp') }}
                                                </a>
                                            </div>
                                        @endif
                                        <div>
                                            {!! make_clickable_path($curDirPath) !!}
                                            @if ($downloadPath)
                                            &nbsp;&nbsp;{!! icon('fa-download', trans('langDownloadDir'), $downloadPath) !!}
                                            @endif
                                        </div>
                                        @if ($curDirName and $dirComment)
                                            <div>{{ $dirComment }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        

                        <div class="row p-2"></div>

                        
                            <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                <div class='table-responsive glossary-categories' style="">
                                    <table class='table' id="document_table" style="overflow: inherit">

                                        <thead class="notes_thead text-light">
                                            <tr>
                                                <th scope="col"><span>{!! headlink(trans('langType'), 'type') !!}</span></th>
                                                <th scope="col"><span>{!! headlink(trans('langName'), 'name') !!}</span></th>
                                                <th scope="col"><span>Κατάσταση</span></th>
                                                <th scope="col"><span>{{ trans('langSize') }}</span></th>
                                                <th scope="col"><span>{!! headlink(trans('langDate'), 'date') !!}</span></th>
                                                <th scope="col"><span>{{trans('langShow')}}</span></th>
                                                @unless ($is_in_tinymce)
                                                    <th class='text-end'>{!! icon('fa-cogs', trans('langCommands')) !!}</th>
                                                @endif
                                            </tr>
                                        </thead>

                                        <tbody>

                                        @forelse ($fileInfo as $file)
            
                                            <tr class="{{ !$file->visible || ($file->extra_path && !$file->common_doc_visible) ? 'not_visible' : 'visible' }}">
                                                
                                                
                                                <td class='text-left'>
                                                    @if($file->visible == 1)
                                                        <span class='visibleFile fa {{ $file->icon }}'></span>
                                                    @else
                                                        <span class='invisibleFile fa {{ $file->icon }}'></span>
                                                    @endif
                                                </td>
                                               
                                                
                                                <td>
                                                    <?php $downloadfile = $base_url . "download=" . getIndirectReference($file->path);?>
                                                    <input type='hidden' value={{$downloadfile}}>
                                                    
                                                    @if ($file->is_dir)
                                                        @if($file->visible == 1)
                                                            <a href='{{ $file->url }}'>{{ $file->filename }}</a>
                                                        @else
                                                            <a class="opacity-50 text-secondary pe-none" href='{{ $file->url }}'>{{ $file->filename }}</a>
                                                        @endif
                                                    @else
                                                        {!! $file->link !!}
                                                    @endif
                                                    @if ($can_upload)
                                                        @if ($file->extra_path)
                                                            @if ($file->common_doc_path)
                                                                @if ($file->common_doc_visible)
                                                                    {!! icon('common', trans('langCommonDocLink')) !!}
                                                                @else
                                                                    {!! icon('common-invisible', trans('langCommonDocLinkInvisible')) !!}
                                                                @endif
                                                            @else
                                                                {!! icon('fa-external-link', trans('langExternalFile')) !!}
                                                            @endif
                                                        @endif
                                                        @if (!$file->public)
                                                            {!! icon('fa-lock', trans('langNonPublicFile')) !!}
                                                        @endif
                                                        @if ($file->editable)
                                                            {!! icon('fa-edit', trans('langEdit'), $file->edit_url) !!}
                                                        @endif
                                                    @endif
                                                    @if ($file->copyrighted)
                                                        {!! icon($file->copyright_icon, $file->copyright_title, $file->copyright_link,
                                                            'target="_blank" style="color:#555555;"') !!}
                                                    @endif
                                                    @if ($file->comment)
                                                        <br>
                                                        <span class='comment text-muted'>
                                                            <small>
                                                                {!! nl2br(e($file->comment)) !!}
                                                            </small>
                                                        </span>
                                                    @endif
                                                </td>


                                                
                                                <td>
                                                    @if($file->updated_message)
                                                        @if($file->visible == 1)
                                                            <button class="btn btn-success UpdateMessage">{{ $file->updated_message }}</button>
                                                        @else
                                                            <button class="btn btn-secondary UpdatedMessage">{{ $file->updated_message }}</button>
                                                        @endif
                                                    @else
                                                        @if($file->visible == 1)
                                                            <button class="btn btn-secondary DocVisible">{{trans('langDocument')}}</button>
                                                        @else
                                                            <button class="btn btn-secondary DocInvisible">{{trans('langDocument')}}</button>
                                                        @endif
                                                    @endif
                                                </td>
                                               


                                                @if ($file->is_dir)
                                                    <td>&nbsp;</td>
                                                    @if($file->visible == 1)
                                                        <td class='center'>{{ $file->date }}</td>
                                                    @else
                                                        <td class='center'><span class="opacity-50 text-secondary">{{ $file->date }}</span></td>
                                                    @endif
                                                    
                                                @elseif ($file->format == '.meta')
                                                    @if($file->visible == 1)
                                                       <td>{{ $file->size }}</td>
                                                       <td class='center'>{{ $file->date }}</td>
                                                    @else
                                                       <td><span class="opacity-50 text-white-50">{{ $file->size }}</span></td>
                                                       <td class='center'><span class="opacity-50 text-white-50">{{ $file->date }}</span></td>
                                                    @endif
                                            
                                                @else
                                                    @if($file->visible == 1)
                                                       <td>{{ $file->size }}</td>
                                                       <td title='{{ nice_format($file->date, true) }}' class='center'>{{ nice_format($file->date, true, true) }}</td>
                                                    @else
                                                       <td><span style="opacity-50 text-white-50">{{ $file->size }}</span></td>
                                                       <td title='{{ nice_format($file->date, true) }}' class='center'><span class="opacity-50 text-white-50">{{ nice_format($file->date, true, true) }}</span></td>
                                                    @endif
                                                    
                                                    
                                                @endif
                                                <td>
                                                    @if($file->visible == 1)
                                                        <span class="text-success"><i class="fas fa-eye"></i> {{trans('langVisible')}}</span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-eye"></i> {{trans('langInvisible ')}}</span>
                                                    @endif
                                                </td>
                                                @unless ($is_in_tinymce)
                                                    <td class='float-end {{ $can_upload? 'option-btn-cell': 'text-end'}}'>
                                                        {!! $file->action_button !!}
                                                    </td>
                                                @endif
                                            </tr>

                                           

                                        @empty
                                            <tr>
                                                <td colspan='5'>
                                                    <p class='not_visible text-center'> - {{ trans('langNoDocuments') }} - </p>
                                                </td>
                                            </tr>
                                            
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        
                    @else
                        <div class='col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2'><div class='alert alert-warning'>{{ trans('langNoDocuments') }}</div></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>





@endsection

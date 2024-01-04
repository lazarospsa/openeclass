<div class="offcanvas offcanvas-start d-lg-none offCanvas-Tools" tabindex="-1" id="offcanvasScrollingTools" aria-labelledby="offcanvasScrollingLabel">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class='col-12 mt-3 d-flex justify-content-center align-items-center'>
                        <img src="{{ $logo_img_small }}">
                    </div>
                    @if(get_config('enable_search'))
                        <div class='col-12 mt-5 d-flex justify-content-center align-items-center px-4'>
                            @if(isset($course_code) and $course_code)
                                <form action="{{ $urlAppend }}modules/search/search_incourse.php?all=true" class='d-flex justify-content-center align-items-end w-100'>
                            @else
                                <form action="{{ $urlAppend }}modules/search/search.php" class='d-flex justify-content-center align-items-end w-100'>
                            @endif
                                    <input type="text" class="inputMobileSearch rounded-0 w-100 basic-value-cl" placeholder="{{ trans('langSearch')}}..." name="search_terms">
                                    <button class="btn d-flex justify-content-center align-items-center rounded-0" type="submit" name="quickSearch">
                                        <i class='fa fa-search small-text'></i>
                                    </button>
                                </form>
                        </div>
                    @endif
                    <div class='col-12 mt-5 mb-3'>
                        <ul class="list-group px-4">
                            @if(!get_config('hide_login_link'))
                                <a id='homeId' type='button' class="list-group-item list-group-item-action toolHomePage rounded-0 d-flex justify-content-start align-items-start" href="{{ $urlServer }}">
                                    <i class="fa fa-home pe-2"></i>{{ trans('langHome') }}
                                </a>
                            @endif
                            @if (!isset($_SESSION['uid']))
                                @if(get_config('registration_link')!='hide')
                                    <a id='registrationId' type="button" class="list-group-item list-group-item-action toolHomePage rounded-0 d-flex justify-content-start align-items-start" href="{{ $urlAppend }}modules/auth/registration.php">
                                        <i class="fa fa-pencil pe-2"></i>{{ trans('langRegistration') }}
                                    </a>
                                @endif
                            @endif

                            @if (!get_config('dont_display_courses_menu'))
                                <a id='coursesId' type='button' class="list-group-item list-group-item-action toolHomePage rounded-0 d-flex justify-content-start align-items-start" href="{{ $urlAppend }}modules/auth/listfaculte.php">
                                    <i class="fa fa-book pe-2"></i>{{ trans('langCourses') }}
                                </a>
                            @endif
                           
                            <a id='faqId' type='button' class="list-group-item list-group-item-action toolHomePage rounded-0 d-flex justify-content-start align-items-start" href="{{ $urlAppend }}info/faq.php">
                                <i class="fa fa-question-circle pe-2"></i>{{ trans('langFaq') }}
                            </a>
                            
                        </ul>
                    </div>

                </div>
            </div>
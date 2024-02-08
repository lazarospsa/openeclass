<div class="panel panelCard px-lg-4 py-lg-3 mt-3">
    <div class="panel-heading border-0 d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <h3 class='mb-0'>
            {{ trans('langPortfolioMainContent') }}
        </h3>
    </div>
    <div class="panel-body" id="portfolio_widget_main" data-widget-area-id="3">
        @foreach ($portfolio_main_area_widgets as $key => $portfolio_main_area_widget)
            <div class="panel{{!isset($myWidgets) || isset($myWidgets) && $portfolio_main_area_widget->is_user_widget ? ' panel-success widget' : ' panel-default opacity-help pe-none'}} mb-3" data-widget-id="{{ $portfolio_main_area_widget->id }}" data-widget-widget-area-id="{{ $key }}">
                <div class="panel-heading">
                    <a class='text-white' data-bs-toggle="collapse" data-bs-target="#widget_desc_{{ $key }}" href="#widget_desc_{{ $key }}" class="widget_title">
                        {{ $portfolio_main_area_widget->getName() }} 
                        <span class='fa fa-arrow-down ms-1'></span>
                        <span class="float-end">{{ $portfolio_main_area_widget->is_user_widget ? trans('langWidgetPersonal') : trans('langWidgetAdmin') }}</span>
                    </a>
                </div>
                @if (!isset($myWidgets) || isset($myWidgets) && $portfolio_main_area_widget->is_user_widget)
                    <div id="widget_desc_{{ $key }}" class="panel-collapse collapse collapsed">
                        <div class="panel-body">
                            {!! $portfolio_main_area_widget->getOptionsForm($key) !!}
                        </div>
                        <div class="panel-footer clearfix d-flex justify-content-center align-items-center">
                            <a href="#" class="remove btn deleteAdminBtn">
                                {{ trans('langDelete') }}
                            </a>
                           
                            <a href="#" class="btn submitAdminBtn submitOptions ms-1">
                                {{ trans('langSubmit') }}
                            </a>
                           
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>


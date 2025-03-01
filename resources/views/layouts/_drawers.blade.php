<!--begin::Drawers-->
<!--begin::Activities drawer-->
<div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities"
    data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'300px', 'md': '450px', 'lg': '600px'}" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
    <div class="card shadow-none border-0 rounded-0">
        <!--begin::Header-->
        <div class="card-header" id="kt_activities_header">
            <h3 class="card-title fw-bold text-dark">Activity Logs</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-custom btn-active-light-primary me-n5"
                    id="kt_activities_close">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </button>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body position-relative" id="kt_activities_body">
            <!--begin::Content-->
            <div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
                data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body"
                data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer" data-kt-scroll-offset="5px">
                <!--begin::Timeline items-->
                <div class="timeline" id="kt_activities_timeline">
                    <!-- Timeline items will be appended here dynamically -->
                </div>
                <!--end::Timeline items-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Body-->
        <!--begin::Footer-->
        <div class="card-footer py-5 text-center" id="kt_activities_footer">
            <a href="{{ route('logs') }}" class="btn btn-bg-body text-primary">
                View All Logs
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                <span class="svg-icon svg-icon-3 svg-icon-primary">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)"
                            fill="currentColor" />
                        <path
                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </a>
        </div>
        <!--end::Footer-->
    </div>
</div>
<!--end::Activities drawer-->

<!--begin::Notifications drawer-->
<div id="kt_notifications" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="notifications"
    data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'300px', 'md': '450px', 'lg': '600px'}" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_notifications_toggle" data-kt-drawer-close="#kt_notifications_close">
    <div class="card shadow-none border-0 rounded-0">
        <!--begin::Header-->
        <div class="card-header" id="kt_notifications_header">
            <h3 class="card-title fw-bold text-dark">Notifications</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-custom btn-active-light-primary me-n5"
                    id="kt_notifications_close">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </button>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body position-relative" id="kt_notifications_body">
            <!--begin::Content-->
            <div id="kt_notifications_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
                data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_notifications_body"
                data-kt-scroll-dependencies="#kt_notifications_header, #kt_notifications_footer"
                data-kt-scroll-offset="5px">
                <!--begin::Timeline items-->
                <div class="timeline" id="kt_notifications_timeline">
                    <!-- Timeline items will be appended here dynamically -->
                </div>
                <!--end::Timeline items-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Body-->
        <!--begin::Footer-->
        <div class="card-footer py-5 text-center" id="kt_notifications_footer">
            <a href="{{ route('notifications') }}" class="btn btn-bg-body text-primary">
                View All Notifications
                <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                <span class="svg-icon svg-icon-3 svg-icon-primary">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)"
                            fill="currentColor" />
                        <path
                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </a>
        </div>
        <!--end::Footer-->
    </div>
</div>
<!--end::Notifications drawer-->

<!--begin::Todo drawer-->
<div id="kt_todos" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="todos" data-kt-drawer-activate="true"
    data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '450px', 'lg': '600px'}"
    data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_todos_toggle" data-kt-drawer-close="#kt_todos_close">
    <div class="card shadow-none border-0 rounded-0 w-100">
        <!--begin::Header-->
        <div class="card-header" id="kt_todos_header">
            <h3 class="card-title fw-bold text-dark">Todos</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-custom btn-active-light-primary me-n5"
                    id="kt_todos_close">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                                fill="currentColor" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                </button>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body position-relative" id="kt_todos_body">
            <div class="container">
                <!--begin::Content-->
                <div id="kt_todos_scroll" class="position-relative scroll-y" data-kt-scroll="true"
                    data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_todos_body"
                    data-kt-scroll-dependencies="#kt_todos_header" data-kt-scroll-offset="5px">
                    <div class="todo">
                        <form id="todo_form" method="POST">
                            @csrf
                            <div class="d-flex justify-content-center">
                                <input type="text" name="text" id="todo_input" placeholder="Add your tasks">
                                <button type="submit" class="btn btn-primary" id="todo_button">Add</button>
                            </div>
                        </form>
                        <ul id="todo_container">
                        </ul>
                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!--end::Body-->
    </div>
</div>
<!--end::Todo drawer-->
<!--end::Drawers-->
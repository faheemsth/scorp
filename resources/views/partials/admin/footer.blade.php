@php
    use App\Models\Utility;

@endphp
<!-- [ Main Content ] end -->
<!-- <footer class="dash-footer">
    <div class="footer-wrapper">
        <div class="">
            <span class="text-muted">
                {{ Utility::getValByName('footer_text') ? Utility::getValByName('footer_text') : __('Copyright ERPGO') }}
                {{ date('Y') }}</span>
        </div>

    </div>
</footer> -->


<!-- Warning Section Ends -->
<!-- Required Js -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>


<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>

<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>

<!-- Apex Chart -->
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>

<script src="{{ asset('js/jscolor.js') }}"></script>

<script src="{{ asset('js/popper.min.js') }}"></script>
{{-- <script src="{{ asset ('js/bootstrap.min.js') }}"></script> --}}

<script>
    var site_currency_symbol_position = '{{ \App\Models\Utility::getValByName('site_currency_symbol_position') }}';
    var site_currency_symbol = '{{ \App\Models\Utility::getValByName('site_currency_symbol') }}';
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>

@if ($message = Session::get('success'))
    <script>
        show_toastr('success', '{!! $message !!}');
    </script>
@endif
@if ($message = Session::get('error'))
    <script>
        show_toastr('error', '{!! $message !!}');
    </script>
@endif



@stack('script-page')


<script>
    function deleteTask(task_id, task_related_id, task_related) {

        if (task_id == undefined || task_related_id == undefined || task_related == undefined) {
            return false;
        }


        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {


                $.ajax({
                    type: 'GET',
                    url: '/organization/' + task_id + '/task-delete',
                    success: function(data) {
                        data = JSON.parse(data);

                        if (data.status == 'success') {
                            show_toastr('Error', data.message, 'success');

                            if (task_related == 'lead') {
                                openSidebar('/get-lead-detail?lead_id=' + task_related_id);
                            } else if (task_related == 'organization') {
                                openSidebar('/get-organization-detail?org_id=' + task_related_id);
                            } else if (task_related == 'deal') {
                                openSidebar('/get-deal-detail?deal_id=' + task_related_id);
                            }

                        }
                    }

                })


            }
        })
    }



    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
    function openSidebar(url) {
        var ww = $(window).width()

        $.ajax({
            type: 'GET',
            url: url,
            success: function(data) {
                data = JSON.parse(data);
                if (data.status == 'success') {
                    $("#mySidenav").html(data.html);
                    $(".block-screen").css('display', 'none');
                }
            }
        });


        if (ww < 500) {
            $("#mySidenav").css('width', ww + 'px');
            $("#main").css('margin-right', ww + 'px');
        } else {
            $("#mySidenav").css('width', '890px');
            $("#main").css('margin-right', "890px");
        }

        $(".block-screen").css('display', 'block');
        $("#body").css('overflow', 'hidden');
    }


    /* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
    function closeSidebar() {
        $("#mySidenav").css("width", '0');
        $("#main").css("margin-right", '0');
        $(".block-screen").css('display', 'none');
        $("#body").css('overflow', 'visible');
    }
</script>


@stack('old-datatable-js')

@if (App\Models\Utility::getValByName('gdpr_cookie') == 'on')
    <script type="text/javascript">
        var defaults = {
            'messageLocales': {
                /*'en': 'We use cookies to make sure you can have the best experience on our website. If you continue to use this site we assume that you will be happy with it.'*/
                'en': "{{ App\Models\Utility::getValByName('cookie_text') }}"
            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'cookieNoticePosition': 'bottom',
            'learnMoreLinkEnabled': false,
            'learnMoreLinkHref': '/cookie-banner-information.html',
            'learnMoreLinkText': {
                'it': 'Saperne di più',
                'en': 'Learn more',
                'de': 'Mehr erfahren',
                'fr': 'En savoir plus'
            },
            'buttonLocales': {
                'en': 'Ok'
            },
            'expiresIn': 30,
            'buttonBgColor': '#d35400',
            'buttonTextColor': '#fff',
            'noticeBgColor': '#000000',
            'noticeTextColor': '#fff',
            'linkColor': '#009fdd'
        };
    </script>
    <script src="{{ asset('js/cookie.notice.js') }}"></script>
@endif



<script>
    $('table tr').hover(
        function() {
            $(this).find('.hyper-link').addClass('hover-text-color');
            $(this).css('background-color', '#f8f9fd');
        },
        function() {
            $(this).find('.hyper-link').removeClass('hover-text-color');
            $(this).css('background-color', '#ffffff');
        }
    );

    feather.replace();
    var pctoggle = document.querySelector("#pct-toggler");
    if (pctoggle) {
        pctoggle.addEventListener("click", function() {
            if (
                !document.querySelector(".pct-customizer").classList.contains("active")
            ) {
                document.querySelector(".pct-customizer").classList.add("active");
            } else {
                document.querySelector(".pct-customizer").classList.remove("active");
            }
        });
    }

    var themescolors = document.querySelectorAll(".themes-color > a");
    for (var h = 0; h < themescolors.length; h++) {
        var c = themescolors[h];

        c.addEventListener("click", function(event) {
            var targetElement = event.target;
            if (targetElement.tagName == "SPAN") {
                targetElement = targetElement.parentNode;
            }
            var temp = targetElement.getAttribute("data-value");
            removeClassByPrefix(document.querySelector("body"), "theme-");
            document.querySelector("body").classList.add(temp);
        });
    }
    //
    // var custthemebg = document.querySelector("#cust-theme-bg");
    // custthemebg.addEventListener("click", function () {
    //     if (custthemebg.checked) {
    //         document.querySelector(".dash-sidebar").classList.add("transprent-bg");
    //         document
    //             .querySelector(".dash-header:not(.dash-mob-header)")
    //             .classList.add("transprent-bg");
    //     } else {
    //         document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
    //         document
    //             .querySelector(".dash-header:not(.dash-mob-header)")
    //             .classList.remove("transprent-bg");
    //     }
    // });

    {{-- var custdarklayout = document.querySelector("#cust-darklayout"); --}}
    {{-- custdarklayout.addEventListener("click", function () { --}}
    {{--    if (custdarklayout.checked) { --}}
    {{--        document --}}
    {{--            .querySelector(".m-header > .b-brand > .logo-lg") --}}
    {{--            .setAttribute("src", "{{ asset('js/chatify/autosize.js') }}../assets/images/logo.svg"); --}}
    {{--        document --}}
    {{--            .querySelector("#main-style-link") --}}
    {{--            .setAttribute("href", "{{ asset('js/chatify/autosize.js') }}../assets/css/style-dark.css"); --}}
    {{--    } else { --}}
    {{--        document --}}
    {{--            .querySelector(".m-header > .b-brand > .logo-lg") --}}
    {{--            .setAttribute("src", "{{ asset('js/chatify/autosize.js') }}../assets/images/logo-dark.svg"); --}}
    {{--        document --}}
    {{--            .querySelector("#main-style-link") --}}
    {{--            .setAttribute("href", "{{ asset('js/chatify/autosize.js') }}../assets/css/style.css"); --}}
    {{--    } --}}
    {{-- }); --}}

    function removeClassByPrefix(node, prefix) {
        for (let i = 0; i < node.classList.length; i++) {
            let value = node.classList[i];
            if (value.startsWith(prefix)) {
                node.classList.remove(value);
            }
        }
    }


    $(".enteries_per_page").on('change', function() {
        var url_params = $('.url_params').val();
        var page_entries = $(this).val();
        var url = "<?= request()->url() ?>";
        window.location.href = url + '?' + url_params + '&num_results_on_page=' + page_entries;
    })
</script>

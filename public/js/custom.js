/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";
// for pos system
var session_key = $(location).attr("href").split('/').pop();
//

$(function () {
    if ($('.custom-scroll').length) {
        $(".custom-scroll").niceScroll();
        $(".custom-scroll-horizontal").niceScroll();
    }


    // loadConfirm();


});

$(document).ready(function () {
    if ($(".datatable").length > 0) {
        const dataTable =  new simpleDatatables.DataTable(".datatable");
    }

     select2();
    // summernote();
    // daterange();
    // loadConfirm();
});


function daterange() {
    if ($("#pc-daterangepicker-1").length > 0) {
        document.querySelector("#pc-daterangepicker-1").flatpickr({
            mode: "range"
        });
    }
}


function show_toastr(type, message) {
   

    var f = document.getElementById('liveToast');
    var toast = new bootstrap.Toast(f);
    if (type == 'success') {
        $('#liveToast').addClass('successmg');
    } else {
        $('#liveToast').addClass('bg-danger');
    }

    $('#liveToast .toast-body').html(message);

    toast.show();
    //$('#liveToast').addClass('show');

    // Hide the toast after 5 seconds
    setTimeout(function() {
        toast.hide();
    }, 5000); // 5000 milliseconds = 5 seconds
}


$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {
    var $button = $(this); // Store a reference to $(this)
    var data = {};
    var title1 = $button.attr("title");
    
    var title2 = $button.attr("data-original-title");
    var title3 = $button.attr("original-title");
    var title = (title1 != undefined && title1 != '') ? title1 : title2;
    title = (title != undefined) ? title : title3; // Combine assignments into one line
    console.log(title);
    
    var size = ($button.data('size') == '') ? 'md' : $button.data('size');
    var url = $button.data('url');

    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);

    if ($('#vc_name_hidden').length > 0) {
        data['vc_name'] = $('#vc_name_hidden').val();
    }
    if ($('#warehouse_name_hidden').length > 0) {
        data['warehouse_name'] = $('#warehouse_name_hidden').val();
    }
    if ($('#discount_hidden').length > 0) {
        data['discount'] = $('#discount_hidden').val();
    }
    var kids = $button.children();
    var className = $(kids[0]).attr('class');

    if ($(kids[0]).hasClass('ti-pencil') || $(kids[0]).hasClass('ti-plus')) {
        $(kids[0]).css('display','none');
        $button.append('<span class="spinner-border spinner-border-sm spnier-updbtn d-none" role="status" aria-hidden="true"></span>');
    }

    $('.spnier-updbtn').removeClass('d-none');
    $button.prop('disabled', true);

    $.ajax({
        url: url,
        data: data,
        success: function (data) {
            $button.prop('disabled', false); // Use the stored reference
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            if ($(kids[0]).hasClass('ti-pencil') || $(kids[0]).hasClass('ti-plus')) {
                $(kids[0]).css('display','block');
                $(".spnier-updbtn").remove();
            }
            taskCheckbox();
            common_bind("#commonModal");
            commonLoader();
            select2();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error');
            if ($(kids[0]).hasClass('ti-pencil') || $(kids[0]).hasClass('ti-plus')) {
                $(kids[0]).css('display','block');
                $(".spnier-updbtn").remove();
            }
        }
    });
});


function select2() {
    if ($("select.select2").length > 0) {
        $("select.select2").each(function (index, element) {
          //  console.log(element);
            // console.log(element);
            var id = $(element).attr('id');
          //  console.log('Element ID:', id); // Add this line for debugging
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                    shouldSort: false
                }
            );
        });
    }
}

function initSelect2() {
    if ($("select.select2").length > 0) {
        $("select.select2").each(function (index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
}



function arrayToJson(form) {
    var data = $(form).serializeArray();
    var indexed_array = {};

    $.map(data, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}


function common_bind() {
    select2();
}


function taskCheckbox() {
    var checked = 0;
    var count = 0;
    var percentage = 0;

    count = $("#check-list input[type=checkbox]").length;
    checked = $("#check-list input[type=checkbox]:checked").length;
    percentage = parseInt(((checked / count) * 100), 10);
    if (isNaN(percentage)) {
        percentage = 0;
    }
    $(".custom-label").text(percentage + "%");
    $('#taskProgress').css('width', percentage + '%');


    $('#taskProgress').removeClass('bg-warning');
    $('#taskProgress').removeClass('bg-primary');
    $('#taskProgress').removeClass('bg-success');
    $('#taskProgress').removeClass('bg-danger');

    if (percentage <= 15) {
        $('#taskProgress').addClass('bg-danger');
    } else if (percentage > 15 && percentage <= 33) {
        $('#taskProgress').addClass('bg-warning');
    } else if (percentage > 33 && percentage <= 70) {
        $('#taskProgress').addClass('bg-primary');
    } else {
        $('#taskProgress').addClass('bg-success');
    }
}


function commonLoader() {
    $('[data-toggle="tooltip"]').tooltip();
    if ($('[data-toggle="tags"]').length > 0) {
        $('[data-toggle="tags"]').tagsinput({tagClass: "badge badge-primary"});
    }


    // $(function(){
    //
    //     var dtToday = new Date();
    //
    //     var month = dtToday.getMonth() + 1;
    //     var day = dtToday.getDate();
    //     var year = dtToday.getFullYear();
    //     if(month < 10)
    //         month = '0' + month.toString();
    //     if(day < 10)
    //         day = '0' + day.toString();
    //
    //     var maxDate = year + '-' + month + '-' + day;
    //
    //     $("input[type='date']").attr('max', maxDate);
    // });

    var e = $(".scrollbar-inner");
    e.length && e.scrollbar().scrollLock()

    var e1 = $(".custom-input-file");
    e1.length && e1.each(function () {
        var e1 = $(this);
        e1.on("change", function (t) {
            !function (e, t, a) {
                var n, o = e.next("label"), i = o.html();
                t && t.files.length > 1 ? n = (t.getAttribute("data-multiple-caption") || "").replace("{count}", t.files.length) : a.target.value && (n = a.target.value.split("\\").pop()), n ? o.find("span").html(n) : o.html(i)
            }(e1, this, t)
        }), e1.on("focus", function () {
            !function (e) {
                e.addClass("has-focus")
            }(e1)
        }).on("blur", function () {
            !function (e) {
                e.removeClass("has-focus")
            }(e1)
        })
    })

    // var e2 = $('[data-toggle="autosize"]');
    // e2.length && autosize(e2);


    if ($(".jscolor").length) {
        jscolor.installByClassName("jscolor");
    }
    summernote();
    // for Choose file
    $(document).on('change', 'input[type=file]', function () {
        var fileclass = $(this).attr('data-filename');
        var finalname = $(this).val().split('\\').pop();
        $('.' + fileclass).html(finalname);
    });
}


function summernote() {
    if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            maxHeight: 300,
            toolbar: [
                ['style', ['style']],
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
            ],

        });
    }


}



$(document).on("click", '.bs-pass-para', function (e) {
    e.preventDefault();
    var form = $(this).closest("form");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            form.submit();

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
        }
    })
});

//only pos system delete button
$(document).on("click", '.bs-pass-para-pos', function () {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "This action can not be undone. Do you want to continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            document.getElementById($(this).data('confirm-yes')).submit();

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
        }
    })
});


function postAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = {_token: token};

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: jdata,
        success: function (data) {
            if (typeof (data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

//end only pos system delete button


function deleteAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = {_token: token};

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'DELETE',
        url: url,
        data: jdata,
        success: function (data) {
            if (typeof (data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

$(document).on('click', '.fc-daygrid-event', function (e) {
    // if (!$(this).hasClass('project')) {
    e.preventDefault();
    var event = $(this);
    var title1 = $('.fc-event-title').html();
    var title2 = $(this).data("bs-original-title");
    var title = (title1 != undefined) ? title1 : title2;
    // var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var size = 'md';
    var url = $(this).attr('href');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .body').html(data);
            $("#commonModal").modal('show');
            common_bind();
        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
    // }
});

//date value 4

// $(function(){
//
//     var dtToday = new Date();
//
//     var month = dtToday.getMonth() + 1;
//     var day = dtToday.getDate();
//     var year = dtToday.getFullYear();
//     if(month < 10)
//         month = '0' + month.toString();
//     if(day < 10)
//         day = '0' + day.toString();
//
//     var maxDate = year + '-' + month + '-' + day;
//
//     $("input[type='date']").attr('max', maxDate);
// });

function addCommas(num) {
    var number = parseFloat(num).toFixed(2).replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    return ((site_currency_symbol_position == "pre") ? site_currency_symbol : '') + number + ((site_currency_symbol_position == "post") ? site_currency_symbol : '');
}



// PLUS MINUS QUANTITY JS
function wcqib_refresh_quantity_increments() {
    jQuery("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").each(function (a, b) {
        var c = jQuery(b);
        c.addClass("buttons_added"),
            c.children().first().before('<input type="button" value="-" class="minus" />'),
            c.children().last().after('<input type="button" value="+" class="plus" />')
    })
}

String.prototype.getDecimals || (String.prototype.getDecimals = function () {
    var a = this,
        b = ("" + a).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
    return b ? Math.max(0, (b[1] ? b[1].length : 0) - (b[2] ? +b[2] : 0)) : 0
}), jQuery(document).ready(function () {
    wcqib_refresh_quantity_increments()
}), jQuery(document).on("updated_wc_div", function () {
    wcqib_refresh_quantity_increments()
}), jQuery(document).on("click", ".plus, .minus", function () {
    var a = jQuery(this).closest(".quantity").find('input[name="quantity"], input[name="quantity[]"]'),
        b = parseFloat(a.val()),
        c = parseFloat(a.attr("max")),
        d = parseFloat(a.attr("min")),
        e = a.attr("step");
    b && "" !== b && "NaN" !== b || (b = 0), "" !== c && "NaN" !== c || (c = ""), "" !== d && "NaN" !== d || (d = 0), "any" !== e && "" !== e && void 0 !== e && "NaN" !== parseFloat(e) || (e = 1), jQuery(this).is(".plus") ? c && b >= c ? a.val(c) : a.val((b + parseFloat(e)).toFixed(e.getDecimals())) : d && b <= d ? a.val(d) : b > 0 && a.val((b - parseFloat(e)).toFixed(e.getDecimals())), a.trigger("change")
});

$(document).on('click', 'input[name="quantity"], input[name="quantity[]"]', function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
        // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});



$(document).ready(function(){
    $(".lead-drive-link").on("focusout", function(){
        var link = $(this).val();
        if(link.trim() == ''){
            return false;
        }

        url = $(".lead-drive-url").val();
        var lead_id = $('.lead-id').val();

        $.ajax({
            url: url,
            method: 'GET',
            data: {
                'link' : link,
                'id' : lead_id
            },
            success: function (data) {

            },
            error: function (data) {
            }
        });
    });




    $(".deal-drive-link").on("focusout", function(){
        var link = $(this).val();
        if(link.trim() == ''){
            return false;
        }

        url = $(".deal-drive-url").val();
        var lead_id = $('.lead-id').val();
        alert(url);
        $.ajax({
            url: url,
            method: 'GET',
            data: {
                'link' : link,
                'id' : lead_id
            },
            success: function (data) {

            },
            error: function (data) {
            }
        });
    });
})

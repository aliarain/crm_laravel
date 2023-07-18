"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.sidebar .nav-link').forEach(function (element) {
        element.addEventListener('click', function (e) {
            let nextEl = element.nextElementSibling;
            let parentEl = element.parentElement;
            if (nextEl) {
                e.preventDefault();
                let mycollapse = new bootstrap.Collapse(nextEl);
                if (nextEl.classList.contains('show')) {
                    mycollapse.hide();
                } else {
                    mycollapse.show();
                    // find other submenus with class=show
                    var opened_submenu = parentEl.parentElement.querySelector(
                        '.submenu.show');
                    // if it exists, then close all of them
                    if (opened_submenu) {
                        new bootstrap.Collapse(opened_submenu);
                    }
                }
            }
        }); // addEventListener
    }) // forEach
});

$(function () {
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
    });

    let bdhc = $('body').hasClass('sidebar-collapse');

    $('.clickable-sidebar').on('click', function () {
        bdhc = $('body').hasClass('sidebar-collapse');
        if (!bdhc) {
            $('.nav-link-text').css({
                'display': 'none',
                'opacity': '0',
                'transition': 'opacity 1s ease-out'
            })
        } else {
            $('.nav-link-text').css({
                'display': 'block',
                'opacity': '1'
            })
        }
        if (!bdhc) {
            $('.submenu.collapse').removeClass('show');
        }
    })


    if ($(window).width() > 992) {
        $('.nav-item.has-submenu').on('mouseover', function () {
            bdhc = $('body').hasClass('sidebar-collapse');
            if (bdhc) {
                $(this).find('.submenu').removeClass('show');
            }
        })
    }

    $('input[name="singledate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'), 10)
    });

    $('#timepicker').bootstrapMaterialDatePicker({
        date: false,
        shortTime: false,
        format: 'HH:mm'
    });

    $('.select2-selection__rendered').addClass('customn-multiple-selection-ul');
    $('.notification_panel, .message_panel, .profile_dropdown_panel').on('click', function (evt) {
        if ($(this).hasClass('show') && !$(evt.target).closest('.common-dropdown-btn').length) {
            $(this).removeClass('show');
        } else {
            $('.notification_panel, .message_panel, .profile_dropdown_panel').removeClass('show');
            $(this).addClass('show');
        }
    })

    $('body').on("click",function (evt) {
        if ($(evt.target).closest('.common-dropdown-btn').length || $(evt.target).closest('.common-dropdown-content').length) {
            return;
        } else {
            $('.common-dropdown-btn').removeClass('show');
        }
    });
});

$('.unread_notification').on('click',function(){
    let id = $(this).data('notification_id');
    let url=$('#readNotification').val();
    $.ajax({
        url: url,
        type: "POST",
        data: {
            id: id,
            _token: $("meta[name='csrf-token']").attr('content')
        },
        success: function (data) {
            if (data['action_url'] != null) {
                window.location.href = data['action_url'];
            }
        }
    });
});

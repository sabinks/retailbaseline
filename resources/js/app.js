require('./bootstrap');
import $ from "jquery";
import 'bootstrap';
import 'metismenu';
import bsCustomFileInput  from 'bs-custom-file-input'
// import DataTable from 'datatables.net'
// import 'datatables.net-bs4'
import 'datatables.net-responsive-bs4'
require( './datatable_extension/dataTables.select.min' );
import 'select2'
// import 'sweetalert'
$(document).ready(() => {
    
    bsCustomFileInput.init();
    $('.dataTable').DataTable();
    
    $('.close-sidebar-btn').click(function () {
        var classToSwitch = $(this).attr('data-class');
        var containerElement = '.app-container';
        $(containerElement).toggleClass(classToSwitch);
        var closeBtn = $(this);

        if (closeBtn.hasClass('is-active')) {
            closeBtn.removeClass('is-active');
        } else {
            closeBtn.addClass('is-active');
        }
    });
    // Sidebar Menu

    setTimeout(function () {
        $(".vertical-nav-menu").metisMenu();
    }, 100);

    $(function () {
        $('[data-toggle="popover"]').popover();
    });

    // BS4 Tooltips
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('.mobile-toggle-nav').click(function () {
        $(this).toggleClass('is-active');
        $('.app-container').toggleClass('sidebar-mobile-open');
    });

    // Responsive
    var resizeClass = function () {
        var win = document.body.clientWidth;
        if (win < 1250) {
            $('.app-container').addClass('closed-sidebar-mobile closed-sidebar');
        } else {
            $('.app-container').removeClass('closed-sidebar-mobile closed-sidebar');
        }
    };


    $(window).on('resize', function () {
        resizeClass();
    });

    resizeClass();

    $('.switchTheme').on('click', function () {
        var classToSwitch = $(this).attr('data-class');
        var containerElement = '.app-header';
        var sidebarContainerElement = '.app-sidebar';

        $('.switchTheme').removeClass('active');
        $(this).addClass('active');

        $(containerElement).attr('class', 'app-header');
        $(containerElement).addClass('header-shadow ' + classToSwitch);

        $(sidebarContainerElement).attr('class', 'app-sidebar');
        $(sidebarContainerElement).addClass('sidebar-shadow ' + classToSwitch);
    });
});
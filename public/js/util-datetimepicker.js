'use strict';

jQuery(function($) {
    moment.locale('fr');

    $(document).on("focus", ".util-datetimepicker:not(.util-datepicker-started), [data-toggle=datetimepicker]:not(.datepicker-started)", function() {
        $(this).addClass('util-datepicker-started');
        attachDateTimePicker($(this));
    });
    $(document).on("focus", ".util-datepicker:not(.util-datepicker-started), [data-toggle=datepicker]:not(.datepicker-started)", function() {
        $(this).addClass('util-datepicker-started');
        attachDatePicker($(this));
    });
    $(document).on("focus", ".util-timepicker:not(.util-datepicker-started), [data-toggle=timepicker]:not(.datepicker-started)", function() {
        $(this).addClass('util-datepicker-started');
        attachTimePicker($(this));
    });
    $(document).on("focus", ".util-yearpicker:not(.util-datepicker-started), [data-toggle=yearpicker]:not(.datepicker-started)", function() {
        $(this).addClass('util-datepicker-started');
        attachYearPicker($(this));
    });
    $(document).on("focus", ".util-datehourpicker:not(.util-datepicker-started), [data-toggle=datetimepicker]:not(.datepicker-started)", function() {
        $(this).addClass('util-datepicker-started');
        attachDateHourPicker($(this));
    });
    $(document).on("focus", ".util-daymonthpicker:not(.util-datepicker-started), [data-toggle=monthpicker]:not(.datepicker-started)", function() {
        $(this).addClass('util-datepicker-started');
        attachDayMonthPicker($(this));
    });
    $(document).on("focus", ".util-monthpicker:not(.util-datepicker-started), [data-toggle=monthpicker]:not(.datepicker-started)", function() {
        $(this).addClass('util-datepicker-started');
        attachMonthPicker($(this));
    });
});

function attachDateTimePicker(element) {
    element.datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language: 'fr',
        autoclose: true,
        minuteStep: 5
    }).on('changeDate', function(ev) {
        var target = $($(this).data('sync'));
        if (target) {

            var start = moment($(this).find('input').val());
            var end = moment(target.val());

           if(end <= start){
               target.val(start.add(1, 'hours').format('YYYY-MM-DD HH:mm'));
           }
        }
    });
}

function attachDateHourPicker(element) {
    element.datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        language: 'fr',
        autoclose: true,
        minuteStep: 60,
        minView: 1
    }).on('changeDate', function(ev) {
        var target = $($(this).data('sync'));
        if (target) {

            var start = moment($(this).find('input').val());
            var end = moment(target.val());

            if(end <= start){
                target.val(start.add(1, 'hours').format('YYYY-MM-DD HH:mm'));
            }
        }
    });
}

function attachDatePicker(element) {
    element.datetimepicker({
        format: 'yyyy-mm-dd',
        language: 'fr',
        autoclose: true,
        minView: 2
    }).on('changeDate', function(ev) {
        var target = $($(this).data('sync'));
        if (target) {

            var start = moment($(this).find('input').val());
            var end = moment(target.val());

            if(end <= start){
                target.val(start.add(1, 'hours').format('YYYY-MM-DD HH:mm'));
            }
        }
    });
}

function attachTimePicker(element) {
    element.datetimepicker({
        format: 'hh:ii',
        language: 'fr',
        autoclose: true,
        startView: 1,
        minView: 0,
        maxView: 1,
        showTitle: false
    }).on('changeDate', function(ev) {
        var target = $($(this).data('sync'));
        if (target) {

            var start = moment($(this).find('input').val());
            var end = moment(target.val());

            if(end <= start){
                target.val(start.add(1, 'hours').format('YYYY-MM-DD HH:mm'));
            }
        }
    });
}

function attachYearPicker(element) {
    element.datetimepicker({
        format: 'yyyy',
        language: 'fr',
        autoclose: true,
        startView: 4,
        minView: 4
    }).on('changeDate', function(ev) {
        var target = $($(this).data('sync'));
        if (target) {

            var start = moment($(this).find('input').val());
            var end = moment(target.val());

            if(end <= start){
                target.val(start.add(1, 'hours').format('YYYY-MM-DD HH:mm'));
            }
        }
    });
}

function attachDayMonthPicker(element) {
    element.datetimepicker({
        format: 'dd-mm',
        language: 'fr',
        autoclose: true,
        startView: 3,
        minView: 2,
        maxView: 3,
    }).on('changeDate', function(ev) {
        var target = $($(this).data('sync'));
        if (target) {

            var start = moment($(this).find('input').val());
            var end = moment(target.val());

            if(end <= start){
                target.val(start.add(1, 'hours').format('YYYY-MM-DD HH:mm'));
            }
        }
    });
}

function attachMonthPicker(element) {
    element.datetimepicker({
        format: 'yyyy-mm',
        language: 'fr',
        autoclose: true,
        startView: 3,
        minView: 3,
        maxView: 3,
    }).on('changeDate', function(ev) {
        var target = $($(this).data('sync'));
        if (target) {

            var start = moment($(this).find('input').val());
            var end = moment(target.val());

            if(end <= start){
                target.val(start.add(1, 'hours').format('YYYY-MM-DD HH:mm'));
            }
        }
    });
}

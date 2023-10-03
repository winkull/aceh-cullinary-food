"use strict";

var app_plugins = {
    bootstrap_datepicker: function(){

        /* in case of update datepicker
         * icons
         *   time: "icon-clock2",
             date: "icon-calendar-full",
             up: "icon-chevron-up",
             down: "icon-chevron-down",
             previous: 'icon-chevron-left',
             next: 'icon-chevron-right',
             today: 'icon-calendar-insert',
             clear: 'icon-trash2',
             close: 'icon-cross'
         *
         */
        if($(".bs-datepicker").length > 0){
            $(".bs-datepicker").datetimepicker({format: "DD-MM-YYYY"});
        }

        if($(".bs-datetimepicker").length > 0){
            $(".bs-datetimepicker").datetimepicker({locale: 'id', format: "DD-MM-YYYY LT"});
        }
        if($(".bs-timepicker").length > 0){
            $(".bs-timepicker").datetimepicker({format: "LT"});
        }

        if($(".bs-datepicker-weekends").length > 0){
            $(".bs-datepicker-weekends").datetimepicker({format: "DD-MM-YYYY", daysOfWeekDisabled: [0, 6]});
        }

        if($(".bs-datepicker-inline").length > 0){
            $(".bs-datepicker-inline").datetimepicker({
                inline: true
            });
        }

        if($(".bs-datepicker-inline-time").length > 0){
            $(".bs-datepicker-inline-time").datetimepicker({
                inline: true,
                sideBySide: true
            });
        }

        if($(".bs-datepicker-inline-years").length > 0){
            $(".bs-datepicker-inline-years").datetimepicker({
                inline: true,
                viewMode: 'years'
            });
        }
    },
};


$(function(){
    app_plugins.loaded();
});

$("document").ready(function() {
    $.datepicker.setDefaults(
        $.extend($.datepicker.regional["ru"])
    );
    $(".date").datepicker();
    $('#docserialno').formatter({
        'pattern': '{{9999}} {{999999}}',
    });
    $('tr').click(function() {
        $('tr', $(this).parent()).removeClass('active');
        $(this).addClass('active');
    });
});
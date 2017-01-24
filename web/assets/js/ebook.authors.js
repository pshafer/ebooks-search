(function ($) {

    $.fn.multifieldList = function(options) {

        var settings = $.extend({
            'elementID': $(this).attr('id'),
            'elementType': $(this).prop("tagName"),
            'prototype': $(this).data('prototype'),
            'fieldCount': $(this).find('li').length,
            'addBtnLabel': '<i class="fa fa-fw fa-plus"></i> Add',
            'removeBtn': '<i class="fa fa-fw fa-minus"></i>',
            'fieldNameTemplate' : '',
            'fieldIDTemplate': ''
        }, options);

        console.log(settings);

        $(this).find('li').each(function(index,value) {
            var removeBtn = $('<button>').addClass('btn btn-xs btn-danger').html(settings.removeBtn).click(removeFieldItem);
            $(this).append(removeBtn);
        });


        var removeBtn = $(settings.removeBtn);
        var addBtn =  $('<button>').addClass('btn btn-xs btn-success').html(settings.addBtnLabel);

        addBtn.click(function(event){
            event.preventDefault();

            var removeBtn = $('<button>').addClass('btn btn-xs btn-danger').html(settings.removeBtn).click(removeFieldItem);

            var idTemplate = settings.fieldIDTemplate;
            var nameTemplate = settings.fieldNameTemplate;

            var input  = $(settings.prototype);
            input.attr('id', idTemplate.replace(/__index__/g, settings.fieldCount));
            input.attr('name', nameTemplate.replace(/__index__/g, settings.fieldCount))
            var item   = $('<li>');

            item.append(input).append(" ");
            item.append(removeBtn);

            $('#' + settings.elementID).append(item);
            settings.fieldCount++;

            console.log(settings.fieldCount);

        });

        this.after(addBtn);


        return this;


        function removeFieldItem(event) {
            event.preventDefault();
            $(this).parent('li').remove();
            settings.fieldCount--;

            $('#' + settings.elementID + ' > li > input').each(function(index, value){
                var idTemplate = settings.fieldIDTemplate;
                var nameTemplate = settings.fieldNameTemplate;

                $(this).attr('id', idTemplate.replace(/__index__/g, index));
                $(this).attr('name', nameTemplate.replace(/__index__/g, index))
            });

            console.log(settings.fieldCount);
        }
    };

}( jQuery ));



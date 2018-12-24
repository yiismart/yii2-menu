$('#itemform-type').on('change', function () {
    var typeInput = $(this),
        type = parseInt(typeInput.val()),
        form = typeInput.closest('form'),
        nameGroup = form.find('.field-itemform-name'),
        urlGroup = form.find('.field-itemform-url'),
        aliasGroup = form.find('.field-itemform-alias');

    if (form.data('typesWithName').indexOf(type) != -1) {
        nameGroup.removeClass('d-none');
    } else {
        nameGroup.addClass('d-none');
    }

    if (form.data('typesWithUrl').indexOf(type) != -1) {
        urlGroup.removeClass('d-none');
    } else {
        urlGroup.addClass('d-none');
    }

    if (form.data('typesWithAlias').indexOf(type) != -1) {
        aliasGroup.removeClass('d-none');
        refreshAliasList(aliasGroup, type);
    } else {
        aliasGroup.addClass('d-none');
    }
});

function refreshAliasList(aliasGroup, type) {
    var select = aliasGroup.find('select');
    select.html('');
    $.get(aliasGroup.data('url'), {type: type}, function (data) {
        if (data['type'] === type) {
            $.each(data['items'], function (alias, value) {
                $('<option>').val(alias).text(value).appendTo(select);
            });
        }
    }, 'json');
};

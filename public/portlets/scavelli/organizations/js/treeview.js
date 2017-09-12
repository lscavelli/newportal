$(function () {
    $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
    $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Comprimi questo ramo').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(':visible')) {
            children.hide('fast');
            $(this).attr('title', 'Espandi questo ramo').find(' > i').addClass('glyphicon-plus-sign').removeClass('glyphicon-minus-sign');
        }
        else {
            children.show('fast');
            $(this).attr('title', 'Comprimi questo ramo').find(' > i').addClass('glyphicon-minus-sign').removeClass('glyphicon-plus-sign');
        }
        e.stopPropagation();
    });
});


if(!$('.callout').length) {
    $('.commentsez').hide();
}

$('.showcommentform').on('click', function (e) {
    $('.commentsez').slideToggle();
});

$.fn.removeWin = function() {
    $('.msgDisabled').slideUp(1000, function () {
        $(this).remove();
    })
}
$('.close').on('click', function(e){
    e.preventDefault();
    $('.msgDisabled').remove();
});
setTimeout($(this).removeWin, 2000);

function docReady() {
    console.log("document ready");

    var _ctrl_index = 1000;
    /* Popolo la lista dei campi nel div */
    $.getJSON("/admin/api/listportlets", function (result) {
        $(result).each(function(index,value) {
            $("#listOfFields").append("<div class='fld' data-portlet_id='"+value.id+"'><i class=\"glyphicon glyphicon-th\"></i>&nbsp;&nbsp;"+value.name+" (ver."+ value.revision +")</div>");
        });
    }).done(function() {
        $(".fld").draggable({
            opacity: 0.8,
            helper: "clone",
            stack: "#about section",
            cursor: "move",
            connectToSortable: ".droppedArea",
        })
    });


    $( ".droppedArea" ).droppable({
        activeClass: "activeDroppable",
        hoverClass: "hoverDroppable",
        accept: ":not(.ui-sortable-helper)",
        drop: function( event, ui ) {
            //var draggable = ui.draggable;
            //var fieldtype = $(draggable).attr("fieldtype");
            //alert(JSON.stringify(fieldtype));
            //draggable[0].id = "CTRL-DIV-"+(_ctrl_index++);
            //$( "<div  class='fld-extensive' fieldtype='"+fieldtype+"'>" +
            //        "</div>").html( draggable.html()+"<div class='sposta'>X</div>"+"<div class='cancella'>X</div>").appendTo( this );
        },

        out: function( event, ui ) {
            //cancella portlet
            //$(this).css("background-color", "");
        },
        over: function( event, ui ) {
            // mostra ombra
        }
    })

    $( ".droppedArea" ).sortable({
        opacity: 0.8,
        connectWith: ".droppedArea",
        handle: '.move-button',
        forceHelperSize: true,
        forcePlaceholderSize: true,
        placeholder: 'placeholder ui-corner-all',
        update: function(event,ui){
            $(ui.item).attr('data-frame', $(this).data('frame'));
            if (!$(ui.item)[0].hasAttribute('id')) {
                $(ui.item).removeClass('fld ui-draggable ui-draggable-handle').removeAttr('Style').addClass('fld-extensive');
                $(ui.item).addClass('noset');
                $(ui.item).attr('data-pageid',$(this).data('page'));
                $(ui.item).html('');
            }
            //$.cookie('splash-cookie', getItems('splash'));
            var data = [];
            $.map($(this).children('.fld-extensive'), function(el) {
                data.push({
                    //id: $(el).attr('id'),
                    pivot_id: $(el).data('pivotid'),
                    portlet_id: $(el).data('portlet_id'),
                    page_id: $(el).data('pageid'),
                    frame: $(el).data('frame'),
                    position: $(el).index()+1 // escludo l'indice 0
                });
            });
            //console.log(JSON.stringify(data));
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //'{{ csrf_token() }}'
                }
            });
            $.ajax({
                //contentType: "application/json",
                url: '/admin/api/saveportlets',
                data: {data: JSON.stringify(data)},
                type: 'POST',
                dataType: 'json',
            }).done(function (response) {
                console.log(response);
                if (!$(ui.item)[0].hasAttribute('id') && response.last_id) {
                    ++_ctrl_index;
                    $(ui.item)[0].id = 'ctrl_'+_ctrl_index;
                    var ctrl = "#"+$(ui.item)[0].id;
                    $(ui.item).attr('data-pivotid',response.last_id);
                    $(ui.item).removeAttr("data-portlet_id");
                    $(ui.item).removeData("portlet_id");
                    updateControls();
                    eventClick(ctrl);
                }
            }).fail(function(response){
                console.log(JSON.stringify(response)+' - Chiamata fallita');
            });
        },
    }).disableSelection();

    updateControls();
    eventClick(".fld-extensive .field-actions");

    function updateControls() {
        $('.fld-extensive').each(function() {
            var id = $(this).attr('id');
            var pageid = $(this).data('pageid');
            var pivotid = $(this).data('pivotid');
            var ctrl = "#"+id;
            if (!($(ctrl).children('.field-actions').length > 0))  {
                $(ctrl).append(getOptions(id));
            }
            $(ctrl).mouseover(function() {
                $(ctrl +' .field-actions').css({"display":"inline"});
            });
            $(ctrl).mouseout(function(){
                $(ctrl +' .field-actions').css({"display":"none"});
            });
            $(ctrl +' .field-actions .del-button').click(function(){
                if (confirm("Sei sicuro di voler cancellare la portlet?")) {
                    $(ctrl).remove();
                    if (pageid && pivotid){
                        $.getJSON ("/admin/pages/"+pageid+"/removePivotId/"+pivotid);
                    }
                } else {
                    return false;
                }
            });
            $(ctrl).on('mouseover', function(e){
                e.preventDefault();
                $(ctrl +' .edit-button').css({"display":"inline"});
            });

            $(ctrl).on('mouseout', function(e){
                e.preventDefault();
                $(ctrl +' .edit-button').css({"display":"none"});
            });
        });
    }

    function eventClick(element) {
        $(element+" .toggle-form").click(function() {
            var ec = $(this).parent().parent();
            //alert(JSON.stringify(ec));
            var url = "/admin/pages/"+ec.data('pageid')+"/configPortlet/"+ec.data('pivotid');
            $('#prefIframe').attr('src', url);
            /**
             * prelevo tutti gli altri dati relativi alla portlet
             * css, js, lista delle porzioni di template, posizione e titolo
             * inserisco i contenuti nei relativi campi del model
             **/
                //$.get( "/pages/"+$(this).data('pageid')+"/configPortlet/"+$(this).data('pivotid'), function( data ) {
                //    $('#tabpreferences').html(data);
                //});
            $('#pivot_id').attr('value', ec.data('pivotid'));
            $('#page_id').attr('value', ec.data('pageid'));
            $.getJSON ("/admin/pages/getpref/"+ec.data('pivotid'), function ( res ) {
                if (res.css) $('#css').text(res.css);
                if (res.js) $('#js').text(res.js);
                if (res.title) $('#title').val(res.title);

                $('#position').empty();
                for(i = 1; i <= res.numportlets; i++) {
                    $('#position').append($('<option>', {
                        value: i,
                        text : i
                    }));
                }
                $('#position').val(res.position);

                $('#template').empty();
                $.each(res.templates, function( index, value ){
                    $('#template').append($('<option>', {
                        value: value,
                        text : value
                    }));
                });
                $('#template').val(res.template);

                $('#comunication').val(res.comunication);
            })
            $('#preferencesModal').modal('toggle');
            //$('#preferencesModal').modal('show')
        });
    }
}

$("#ctrl_1001").hover(function() {
    //$(".field-actions").css('display:inline; opacity:100');
    alert('ok');
});

function getFields() {
    $.getJSON("/admin/api/listportlets", function (result) {
        $(result).each(function(index,value) {
            $("#listOfFields").append("<div class='fld' id='portlet_"+value.id+"' fieldtype='"+value.id+"'>"+value.name+" (ver."+ value.revision +")</div>");
        });
    });
    $(".fld").draggable({
        helper: "clone",
        stack: "#about section",
        cursor: "move",
        connectToSortable: ".droppedArea",
    }).addClass('ui-widget ui-widget-content ui-helper-clearfix ui-corner-all')
    //arr.push( {label:'Dropdown', type:'dropdown'});
}

function getOptions(id) {
    return "<div class=\"field-actions\">" +
        "<a id=\"edit_"+id+"\" class=\"toggle-form btn btn-info\" title=\"Imposta elemento\"><i class=\"glyphicon glyphicon-cog\"></i></a>" +
        "<a id=\"copy_"+id+"\" class=\"move-button btn btn-info\" title=\"Muovi elemento\"><i class=\"glyphicon glyphicon-move\"></i></a>" +
        "<a id=\"del_"+id+"\" class=\"del-button btn btn-danger\" title=\"Rimuovi elemento\"><i class=\"glyphicon glyphicon-remove\"></i></a>"
    "</div>"
}
//data-toggle=\"modal\" data-target=\"#preferencesModal\"

$(document).ready(docReady);

//var presets = $('.portlet-chooser ul li');

$('.portlet-chooser .toggler').on('click', function(e){
    e.preventDefault();
    $(this).closest('.portlet-chooser').toggleClass('opened');
});


function searchPortlet(q) {
    //console.log(q);
    $("#listOfFields .fld").each(function() {
        if ($(this).text().search(new RegExp(q, "i")) > -1) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

/*$('.portlet-chooser ul li a').on('click', function(e){
 e.preventDefault();
 presets.removeClass('active');
 $(this).parent().addClass('active');
 //$('#css-preset').removeAttr('href').attr('href', 'css/presets/preset' + $(this).parent().data('preset') + '.css');
 })*/

$('#submitPreferences').on('click', function(e){
    e.preventDefault();
    var data = [];
    var formPortlet = $("#prefIframe").contents().find('#preferencePortlet');
    if (formPortlet.length>0) {
        data = formPortlet.serializeArray();
    }

    data = $.merge($('#otherForm').serializeArray(),data);
    data = $.merge($('#jsForm').serializeArray(),data);
    data = $.merge($('#cssForm').serializeArray(),data);
    data = $.merge($('#pivot_id').serializeArray(),data);
    data = $.merge($('#page_id').serializeArray(),data);

    //console.log(data);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') //'{{ csrf_token() }}'
        }
    });
    $.ajax({
        url: '/admin/pages/savepref',
        data: {data: JSON.stringify(data)},
        type: 'POST',
        dataType: 'json',
    }).done(function (response) {
        //console.log(response);
        $('#preferencesModal').modal('toggle');
        window.location.reload(true);
    }).fail(function(response){
        //console.log(JSON.stringify(response)+' - Chiamata fallita');
    });

    //$("#prefIframe").contents().find('#slectContentForm')
});
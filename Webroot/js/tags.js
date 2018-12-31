$(document).ready(function(){
    var count = $("#tag-number").val() + 1;
    $('#add-tag').on('click', function() {
        var tag = $("#input-tag").val();
        if (tag != "")
        {
            $("#tags").append(
                '<div id="tag-div-' + count + '" class="tag-div">' +
                '<input name="tag_' + count + '" class="tags" type="hidden" value="' + tag + '">' +
                '<p>' + tag + '</p>' +
                '<a id="tag-del-' + count + '" class="btn-floating btn-large waves-effect waves-light btn-tag-del red"><i class="material-icons">clear</i></a>' +
                '</div>');
            $("#input-tag").val("");
            count++;
        }
    });
    $('body main .container form #tags').on('click', function() {
        $('.tag-div .btn-tag-del').on('click', function() {
            var number = $(this).attr('id').substr(8,2);
            $("#tag-div-"+number).remove();
        });

    });
});



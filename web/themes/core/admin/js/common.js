var COMMON = {
    startLoading: function () {
        $('#loading').removeClass('hidden');
    },
    stopLoading: function () {
        $('#loading').addClass('hidden');
    },
    showAlert: function(msg, type, button) {

        // $('.alert-common').removeClass('alert-success').removeClass('alert-info').removeClass('alert-warning').removeClass('alert-danger').addClass('hidden');
        // switch (type) {
        //     case 1:
        //         $('.alert-common').addClass('alert-success').html(msg);
        //         break;
        //     case 2:
        //         $('.alert-common').addClass('alert-info').html(msg);
        //         break;
        //     case 3:
        //         $('.alert-common').addClass('alert-warning').html(msg);
        //         break;
        //     case 4:
        //         $('.alert-common').addClass('alert-danger').html(msg);
        //         break;
        //     default :
        //         $('.alert-common').addClass('alert-info').html(msg);
        //         break;
        // }
        // $('.alert-common').removeClass('hidden');
        switch (type) {
            case 0:
                $('#modal .modal-dialog ').removeClass('modal-success').addClass('modal-error');
                break;
            case 1:
                $('#modal .modal-dialog ').removeClass('modal-error').addClass('modal-success');
                break;
            case 2:
                $('#modal .modal-dialog ').removeClass('modal-error').removeClass('modal-success');
                break;
        }
        if (button && button.href != undefined && button.title != undefined) {
            var btn = '<a class="button button-action" href="' + button.href + '">' + button.title + '</a>';
            $('#modal').find('.modal-footer').find('.button-action').remove();
            $('#modal').find('.modal-footer').prepend(btn);
        }
        $('#modal').find('.modal-body .desc').html(msg);
        $('#modal').modal();
    },
    alertDeleteEvent : function() {
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var conf = confirm("Are you sure ?");
            if (conf == true) {
                window.location.href = $(this).attr('href');
            }
        });
    }

};

$(window).load(function() {
    COMMON.stopLoading();
    COMMON.alertDeleteEvent();
});


function EL(id) {
    return document.getElementById(id);
}

function supports_canvas() {
    return !!document.createElement('canvas').getContext;
}

function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}


let token;

$(document).ready(function() {
    token = $('html').find('meta[name="csrf-token"]').attr('content');

    // Toggle strikethrough class on list items when marked as complete
    $('body').on('change', '[data-trigger="complete-item"]', function() {
        let done;
        let id = $(this).attr('data-id');

        if ($(this).is(':checked')) {
            $(this).siblings().addClass('strikethrough');
            done = 1;
        } else {
            $(this).siblings().removeClass('strikethrough');
            done = 0;
        }

        updateItem(id, done);
    });

    // Show add task modal
    $('body').on('click tap', '[data-trigger="add-item"]', function(event) {
        event.preventDefault();

        $('#add-item-form').find('input[name="listId"]').val($(this).attr('data-id'));
        $('#add-item-modal').modal('show');
    });

    // On submit of add task
    // On new transaction form submit, attempt to add transaction
    $('#add-item-form').on('submit', function(event) {
        event.preventDefault();

        let $alert = $('#add-item-error');
        let form = document.getElementById('add-item-form');
        let formData = new FormData(form);
        formData.append('listId', $('#add-item-form').find('input[name="listId"]').val());

        // Hide existing messages
        $('body').find('[data-target="add-item-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');

        $.ajax({
            url: '/todo/addItem',
            type: 'POST',
            dataType: 'JSON',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': token
            }
        }).done(function(data) {
            if (data.error) {
                // Show errors
                $alert.find('p').html(data.message);

                if (data.hasOwnProperty('validation')) {
                    $.each(data.validation, function(index, val) {
                        $validation = $('body').find(`[data-target="${index}-errors"]`);
                        $validation.find('strong').html(val);
                        $validation.removeClass('d-none');
                    });
                }

                $alert.removeClass('d-none');
                return;
            }
        });
    });

    // On modal close reset the form and the messages
    $('#add-item-modal').on('hidden.bs.modal', function (event) {
        // Reset the form
        $('#add-item-form').trigger('reset');

        // Hide existing messages
        $('body').find('[data-target="transaction-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');
    });
});

function updateItem(id, done) {
    $.ajax({
        url: '/todo/toggleItemCompleteStatus',
        type: 'POST',
        dataType: 'JSON',
        data: {
            id,
            done
        },
        headers: {
            'X-CSRF-TOKEN': token
        }
    }).done(function(data) {
        if (data.error) {
            return;
        }
    });
}

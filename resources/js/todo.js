let token;

$(document).ready(function() {
    token = $('html').find('meta[name="csrf-token"]').attr('content');

    // Toggle strikethrough class on list items when marked as complete
    $('body').on('change', '[data-trigger="complete-item"]', function() {
        let done;
        let id = $(this).attr('data-id');
        let listId = $(this).attr('data-list-id');

        if ($(this).is(':checked')) {
            $(this).siblings().addClass('strikethrough');
            done = 1;
        } else {
            $(this).siblings().removeClass('strikethrough');
            done = 0;
        }

        updateItem(id, done, listId);
    });

    // Show add task modal
    $('body').on('click tap', '[data-trigger="add-item"]', function(event) {
        event.preventDefault();

        $('#add-item-form').find('input[name="listId"]').val($(this).attr('data-id'));
        $('#add-item-modal').modal('show');
    });

    // On submit of add task
    $('#add-item-form').on('submit', function(event) {
        event.preventDefault();

        let listId = $('#add-item-form').find('input[name="listId"]').val();
        let $alert = $('#add-item-error');
        let form = document.getElementById('add-item-form');
        let formData = new FormData(form);
        formData.append('listId', listId);

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

            refreshItems(listId);
        });
    });

    // On modal close reset the form and the messages
    $('#add-item-modal').on('hidden.bs.modal', function (event) {
        // Reset the form
        $('#add-item-form').trigger('reset');

        // Hide existing messages
        $('body').find('[data-target="add-item-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');
    });

    // User wants to add a new list
    // On submit of add list
    $('#add-list-form').on('submit', function(event) {
        event.preventDefault();

        let categoryId = $('body').find('[data-trigger="category-item"].disabled').attr('data-id');
        let $alert = $('#add-list-error');
        let form = document.getElementById('add-list-form');
        let formData = new FormData(form);
        formData.append('categoryId', categoryId);

        // Hide existing messages
        $('body').find('[data-target="add-list-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');

        $.ajax({
            url: '/todo/addList',
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

            refreshLists(categoryId, data.id);
        });
    });

    // User wants to add a new category
    // On submit of add category
    $('#add-category-form').on('submit', function(event) {
        event.preventDefault();

        let $alert = $('#add-category-error');
        let form = document.getElementById('add-category-form');
        let formData = new FormData(form);

        // Hide existing messages
        $('body').find('[data-target="add-category-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');

        $.ajax({
            url: '/todo/addCategory',
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

            window.location = `/${data.id}`;
        });
    });

    // User wants to edit a category
    $('body').on('click tap', '[data-trigger="edit-category"]', function(event) {
        event.preventDefault();

        let id = $(this).attr('data-id');
        let $form = $('#edit-category-form');
        $form.find('input[name="categoryId"]').val(id);

        $.ajax({
            url: `/todo/getCategory/${id}`,
            type: 'GET',
            dataType: 'JSON',
            headers: {
                'X-CSRF-TOKEN': token
            }
        }).done(function(data) {
            if (data.error) {
                // Show global error?
                return;
            }

            $form.find('input[name="title"]').val(data.category.title);
            $('#edit-category-modal').modal('show');
        });
    });

    // User wants to edit a category
    // On submit of edit category
    $('#edit-category-form').on('submit', function(event) {
        event.preventDefault();

        let categoryId = $('#edit-category-form').find('input[name="categoryId"]').val();
        let $alert = $('#edit-category-error');
        let form = document.getElementById('edit-category-form');
        let formData = new FormData(form);
        formData.append('categoryId', categoryId);

        // Hide existing messages
        $('body').find('[data-target="edit-category-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');

        $.ajax({
            url: '/todo/editCategory',
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

            location.reload();
        });
    });

    // User wants to edit a list
    $('body').on('click tap', '[data-trigger="edit-list"]', function(event) {
        event.preventDefault();

        let id = $(this).attr('data-id');
        let $form = $('#edit-list-form');
        $form.find('input[name="listId"]').val(id);

        $.ajax({
            url: `/todo/getList/${id}`,
            type: 'GET',
            dataType: 'JSON',
            headers: {
                'X-CSRF-TOKEN': token
            }
        }).done(function(data) {
            if (data.error) {
                // Show global error?
                return;
            }

            $form.find('input[name="title"]').val(data.list.title);
            $('#edit-list-modal').modal('show');
        });
    });

    // User wants to edit a list
    // On submit of edit list
    $('#edit-list-form').on('submit', function(event) {
        event.preventDefault();

        let categoryId = $('body').find('[data-trigger="category-item"].disabled').attr('data-id');
        let listId = $('#edit-list-form').find('input[name="listId"]').val();
        let $alert = $('#edit-list-error');
        let form = document.getElementById('edit-list-form');
        let formData = new FormData(form);
        formData.append('listId', listId);

        // Hide existing messages
        $('body').find('[data-target="edit-list-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');

        $.ajax({
            url: '/todo/editList',
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

            refreshLists(categoryId, listId);
        });
    });

    // User wants to edit an item
    $('body').on('click tap', '[data-trigger="edit-item"]', function(event) {
        event.preventDefault();

        let id = $(this).attr('data-id');
        let $form = $('#edit-item-form');
        $form.find('input[name="itemId"]').val(id);

        $.ajax({
            url: `/todo/getItem/${id}`,
            type: 'GET',
            dataType: 'JSON',
            headers: {
                'X-CSRF-TOKEN': token
            }
        }).done(function(data) {
            if (data.error) {
                // Show global error?
                return;
            }

            $form.find('input[name="title"]').val(data.item.title);
            $('#edit-item-modal').modal('show');
        });
    });

    // User wants to edit a item
    // On submit of edit item
    $('#edit-item-form').on('submit', function(event) {
        event.preventDefault();

        let listId = $('body').find('[data-parent="#list-accordion"].show').attr('data-id');
        let itemId = $('#edit-item-form').find('input[name="itemId"]').val();
        let $alert = $('#edit-item-error');
        let form = document.getElementById('edit-item-form');
        let formData = new FormData(form);
        formData.append('listId', itemId);

        // Hide existing messages
        $('body').find('[data-target="edit-item-alert"]').addClass('d-none');
        $('body').find('.help-block').addClass('d-none');

        $.ajax({
            url: '/todo/editItem',
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

            refreshItems(listId);
        });
    });
});

function updateItem(id, done, listId) {
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

        updateListItemCount(listId, 'status', done);
    });
}

function updateListItemCount(listId, action, done = false) {
    let count;
    let $ele;
    let $container = $('body').find(`[data-target="list-count-container-${listId}"]`);

    if (action == 'status') {
        $ele = $('body').find(`[data-target="list-item-done-count-${listId}"]`);
        count = parseInt($ele.html());
        count = done == 1 ? count+1 : count-1;
        $ele.html(count);
    } else {
        $ele = $('body').find(`[data-target="list-item-count-${listId}"]`);
        count = parseInt($ele.html());
        $ele.html(count+1);
    }

    $container.removeClass('d-none');
}

function refreshItems(listId) {
    let $itemsContainer = $(`#list-${listId}`).find('[data-target="items-container"]');
    let $spinner = $(`#loading-spinner-${listId}`);

    $itemsContainer.addClass('d-none');
    $spinner.removeClass('d-none');

    $.ajax({
        url: '/todo/getListItems',
        type: 'POST',
        dataType: 'JSON',
        data : { listId },
        headers: {
            'X-CSRF-TOKEN': token
        }
    }).done(function(data) {
        if (data.error) {
            // Ajax update of content failed so refresh full page instead
            location.reload();
            return;
        }

        // Update content
        $itemsContainer.html(data.html);
        $spinner.addClass('d-none');
        $itemsContainer.removeClass('d-none');

        // Update item count
        updateListItemCount(listId, 'add-item')

        // Close modal
        $('#add-item-modal').modal('hide');
    });
}

function refreshLists(categoryId, listId) {
    let $categoryContainer = $('body').find('[data-target="category-container"]');
    let $spinner = $(`#category-loading-spinner`);

    $categoryContainer.addClass('d-none');
    $spinner.removeClass('d-none');

    $.ajax({
        url: '/todo/getCategoryLists',
        type: 'POST',
        dataType: 'JSON',
        data : {
            categoryId,
            listId
         },
        headers: {
            'X-CSRF-TOKEN': token
        }
    }).done(function(data) {
        if (data.error) {
            // Ajax update of content failed so refresh full page instead
            location.reload();
            return;
        }

        // Update content
        $categoryContainer.html(data.html);
        $spinner.addClass('d-none');
        $categoryContainer.removeClass('d-none');

        // Close modals
        $('#add-list-modal').modal('hide');
        $('#edit-list-modal').modal('hide');
    });
}

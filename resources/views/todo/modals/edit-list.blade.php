<div class="modal fade" id="edit-list-modal" tabindex="-1" role="dialog" aria-labelledby="edit-list-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-list-modal-label">@lang('todo.edit-list')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" role="alert" id="edit-list-error" data-target="edit-list-alert">
                    <p class="mb-0 pb-0">@lang('todo.form-error')</p>
                </div>
                <form id="edit-list-form">
                    <input type="hidden" name="categoryId">
                    <input type="hidden" name="listId">
                    <div class="form-group">
                        <label for="edit-list-title">@lang('todo.list-title')</label>
                        <input type="text" class="form-control" id="edit-list-title" name="title" placeholder="E.g. Shopping List" required>
                        <span class="help-block d-none" data-target="title-errors">
                            <strong></strong>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="edit-list-form" class="btn btn-primary">@lang('todo.save')</button>
                <button data-trigger="delete-list" class="btn btn-danger">@lang('todo.delete')</button>
            </div>
        </div>
    </div>
</div>

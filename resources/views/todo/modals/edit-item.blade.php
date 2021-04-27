<div class="modal fade" id="edit-item-modal" tabindex="-1" role="dialog" aria-labelledby="edit-item-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-item-modal-label">@lang('todo.edit-item')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" role="alert" id="edit-item-error" data-target="edit-item-alert">
                    <p class="mb-0 pb-0">@lang('todo.form-error')</p>
                </div>
                <form id="edit-item-form">
                    <input type="hidden" name="itemId">
                    <div class="form-group">
                        <label for="edit-item-title">@lang('todo.item-title')</label>
                        <input type="text" class="form-control" id="edit-item-title" name="title" placeholder="E.g. Cook dinner" required>
                        <span class="help-block d-none" data-target="title-errors">
                            <strong></strong>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="edit-item-form" class="btn btn-primary">@lang('todo.save')</button>
                <button data-trigger="delete-item" class="btn btn-danger">@lang('todo.delete')</button>
            </div>
        </div>
    </div>
</div>

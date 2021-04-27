<div class="modal fade" id="edit-category-modal" tabindex="-1" role="dialog" aria-labelledby="edit-category-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-category-modal-label">@lang('todo.edit-category')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" role="alert" id="edit-category-error" data-target="edit-category-alert">
                    <p class="mb-0 pb-0">@lang('todo.form-error')</p>
                </div>
                <form id="edit-category-form">
                    <input type="hidden" name="categoryId">
                    <div class="form-group">
                        <label for="edit-category-title">@lang('todo.category-title')</label>
                        <input type="text" class="form-control" id="edit-category-title" name="title" placeholder="E.g. Shopping List" required>
                        <span class="help-block d-none" data-target="title-errors">
                            <strong></strong>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="edit-category-form" class="btn btn-primary">@lang('todo.save')</button>
                <button data-trigger="delete-category" class="btn btn-danger">@lang('todo.delete')</button>
            </div>
        </div>
    </div>
</div>

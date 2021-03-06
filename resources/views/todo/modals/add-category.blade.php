<div class="modal fade" id="add-category-modal" tabindex="-1" role="dialog" aria-labelledby="add-category-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-category-modal-label">@lang('todo.new-category')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" role="alert" id="add-category-error" data-target="add-category-alert">
                    <p class="mb-0 pb-0">@lang('todo.form-error')</p>
                </div>
                <form id="add-category-form">
                    <div class="form-group">
                        <label for="category-title">@lang('todo.category-title')</label>
                        <input type="text" class="form-control" id="category-title" name="title" placeholder="E.g. Shopping List" required>
                        <span class="help-block d-none" data-target="title-errors">
                            <strong></strong>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="add-category-form" class="btn btn-primary">@lang('todo.add')</button>
            </div>
        </div>
    </div>
</div>

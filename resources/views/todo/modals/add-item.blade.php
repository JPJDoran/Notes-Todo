<div class="modal fade" id="add-item-modal" tabindex="-1" role="dialog" aria-labelledby="add-item-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-item-modal-label">@lang('todo.new-item')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" role="alert" id="add-item-error" data-target="add-item-alert">
                    <p class="mb-0 pb-0">@lang('todo.form-error')</p>
                </div>
                <form id="add-item-form">
                    <input type="hidden" name="listId">
                    <div class="form-group">
                        <label for="item-title">@lang('todo.item-title')</label>
                        <input type="text" class="form-control" id="item-title" name="title" placeholder="E.g. Cook dinner" required>
                        <span class="help-block d-none" data-target="title-errors">
                            <strong></strong>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="add-item-form" class="btn btn-primary">@lang('todo.add')</button>
            </div>
        </div>
    </div>
</div>

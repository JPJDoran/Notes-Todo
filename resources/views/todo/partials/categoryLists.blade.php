@if ($lists->isEmpty())
    <div class="alert alert-warning" role="alert">
        @lang('todo.no-lists')
    </div>
@else
    <div class="accordion" id="list-accordion">
        @foreach ($lists as $key => $list)
            <div class="card">
                <div class="card-header" id="list-title-{{ $list->id }}">
                    <h2 class="mb-0">
                        <button class="btn btn-link p-0" type="button" data-toggle="collapse" data-target="#list-{{ $list->id }}" aria-expanded="true" aria-controls="list-{{ $list->id }}">
                            {{ $list->title }}

                            <span class="{{ $list->Items->isEmpty() ? 'd-none' : '' }}" data-target="list-count-container-{{ $list->id }}">
                                -
                                <span data-target="list-item-done-count-{{ $list->id }}">
                                    {{ count($list->Items->where('done', 1)->all()) }}
                                </span>
                                /
                                <span data-target="list-item-count-{{ $list->id }}">
                                    {{ count($list->Items) }}
                                </span>
                                @lang('todo.complete')
                            </span>
                        </button>
                    </h2>
                </div>

                <div id="list-{{ $list->id }}" class="collapse {{ $list->id == $listId ? 'show' : 'collapsed' }}" aria-labelledby="list-title-{{ $list->id }}" data-parent="#list-accordion">
                    <div class="card-body">
                        <div class="alert alert-secondary mb-0 d-none" role="alert" id="loading-spinner-{{ $list->id }}">
                            <p class="mb-0 pb-0">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                            </p>
                        </div>
                        <div data-target="items-container">
                            @if ($list->Items->isEmpty())
                                <div class="alert alert-warning" role="alert">
                                    @lang('todo.no-items')
                                </div>
                            @else
                                @foreach ($list->Items as $item)
                                    <div class="form-check mt-3 mb-3">
                                        <input class="form-check-input" type="checkbox" id="item-{{ $item->id }}" data-id="{{ $item->id }}" data-list-id="{{ $list->id }}" data-trigger="complete-item" {{ $item->done ? 'checked' : '' }}>
                                        <label class="form-check-label {{ $item->done ? 'strikethrough' : '' }}" for="item-{{ $item->id }}">
                                            {{ $item->title }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif

                            <p class="mt-3 mb-0">
                                <a href="#" data-trigger="add-item" data-id="{{ $list->id }}">
                                    <i class="fas fa-plus"></i> @lang('todo.new-item')
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

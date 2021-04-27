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

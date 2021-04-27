@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="card-title">@lang('todo.categories')</h3>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#add-category-modal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        @if (!$categories->isEmpty())
                            <ul class="nav list-group list-group-flush" role="tablist">
                                @foreach ($categories as $key => $category)
                                    <li class="p-0 nav-item">
                                        <h5>
                                            <a class="nav-link {{ $key == 0 ? 'disabled' : false }}" href="#" data-trigger="category-item" data-id="{{ $category->id }}">
                                                {{ $category->title }}
                                            </a>
                                        </h5>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3 class="card-title">@lang('todo.lists')</h3>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#add-list-modal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="alert alert-secondary mb-0 d-none" role="alert" id="category-loading-spinner">
                            <p class="mb-0 pb-0">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                            </p>
                        </div>
                        <div data-target="category-container">
                            @if ($categories->isEmpty())
                                <div class="alert alert-warning" role="alert">
                                    @lang('todo.no-categories')
                                </div>
                            @else
                                @if ($categories->first()->Lists->isEmpty())
                                    <div class="alert alert-warning" role="alert">
                                        @lang('todo.no-lists')
                                    </div>
                                @else
                                    <div class="accordion" id="list-accordion">
                                        @foreach ($categories->first()->Lists as $key => $list)
                                            <div class="card">
                                                <div class="card-header" id="list-title-{{ $list->id }}">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-link p-0" type="button" data-toggle="collapse" data-target="#list-{{ $list->id }}" aria-expanded="true" aria-controls="list-{{ $list->id }}">
                                                            {{ $list->title }}

                                                            <span class="{{ $list->Items->isEmpty() ? 'd-none' : '' }}" data-target="list-count-container">
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

                                                <div id="list-{{ $list->id }}" class="collapse {{ $key == 0 ? 'show' : 'collapsed' }}" aria-labelledby="list-title-{{ $list->id }}" data-parent="#list-accordion">
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('todo.modals.add-item')
    @include('todo.modals.add-list')
    @include('todo.modals.add-category')
@endsection

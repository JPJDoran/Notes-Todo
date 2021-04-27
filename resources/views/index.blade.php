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
                                        <div class="row">
                                            <div class="col">
                                                <h5>
                                                    <a class="nav-link {{ $category->id == $chosenCategory ? 'disabled' : false }}" href="/{{ $category->id }}" data-trigger="category-item" data-id="{{ $category->id }}">
                                                        {{ $category->title }}
                                                    </a>
                                                </h5>
                                            </div>
                                            <div class="col text-right">
                                                <button class="btn btn-primary btn-sm" type="button" data-trigger="edit-category" data-id="{{ $category->id }}">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col mt-3 mt-md-0">
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
                            @if (empty($category))
                                <div class="alert alert-warning" role="alert">
                                    @lang('todo.no-categories')
                                </div>
                            @else
                                {!! $listsPartial !!}
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

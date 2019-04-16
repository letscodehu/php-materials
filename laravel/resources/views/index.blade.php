@extends('layouts.frontend')
@section('content')
    <!-- Blog Entries Column -->
    <div class="col-md-8">

        <h1 class="my-4">{{trans('main_page.title')}}
            <small>{{trans('main_page.subtitle')}}</small>
        </h1>
        @foreach($model->getContent() as $post)
        <!-- Blog Post -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">{{$post->getTitle()}}</h2>
                <p class="card-text">{{$post->getExcerpt()}}</p>
                <a href="{{$post->getLink()->getUrl()}}" class="btn btn-primary">{{$post->getLink()->getTitle()}}</a>
            </div>
            <div class="card-footer text-muted d-flex justify-content-between">
                <div>
                    Posted on {{$post->getPublished()}} by {{$post->getAuthorName()}}
                </div>
                <div>
                    @foreach($post->getCategories() as $category)
                        <span class="badge-secondary badge">{{$category}}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{$model->getContent()->render('pagination::bootstrap-4')}}
        </div>


    </div>

    @include('fragments.sidebar')

@endsection
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
            <div class="card-footer text-muted">
                Posted on {{$post->getPublished()}} by
                <a href="#">{{$post->getAuthorName()}}</a>
            </div>
        </div>
        @endforeach
        <!-- Pagination -->
        <ul class="pagination justify-content-center mb-4">
            <li class="page-item">
                <a class="page-link" href="#">&larr; Older</a>
            </li>
            <li class="page-item disabled">
                <a class="page-link" href="#">Newer &rarr;</a>
            </li>
        </ul>

    </div>

    @include('fragments.sidebar')

@endsection
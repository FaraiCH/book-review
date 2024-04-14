@extends('layouts.app')

@section('content')
<h1 class="mb-10 text-2x1">Books</h1>

    <form method="GET", action="{{route('books.index')}}">
        <input type="text" name="title" />
    </form>
<ul>
    @forelse ($books as $book)
        <li class="mb-4">
            <div class="book-item">
                <div
                    class="flex flex-wrap items-center justify-between">
                    <div class="w-full flex-grow sm:w-auto">
                        <a href="{{route('books.show', $book)}}" class="book-title">{{$book->title}}</a>
                        <span class="book-author">{{$book->author}}</span>
                    </div>
                    <div>
                        <div class="book-rating">
                            {{number_format($book->reviews_avg_rating)}}
                        </div>
                        <div class="book-review-count">
                            out of {{$book->review_count}} {{Str::plural('review', $book->reviews_count)}}
                        </div>
                    </div>
                </div>
            </div>
        </li>
    @empty
        <li class="mb-4">
            <div class="empty-book-item">
                <p class="empty-text">No books found</p>
                <a href="{{route('books.index')}}" class="reset-link">Reset criteria</a>
            </div>
        </li>
    @endforelse
</ul>
@endsection

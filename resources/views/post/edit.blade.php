@extends('layout.base')

@section('content')
    <div class="container mt-5">
        <h1>Edit Post</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Posts</a>
        </div>
        <form action="{{ url('posts/' . $post->id) }}" method="POST" class="bg-light p-5 rounded shadow-sm">
            @csrf
            @method('PUT')
            <div class="form-group mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="5" required>{{ $post->description }}</textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection

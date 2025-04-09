<!-- resources/views/documents/view.blade.php -->

@extends('filament::layouts.app')

@section('content')
    <h1>Document: {{ $document->title }}</h1>
    
    @if ($document->isProtected())
        @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('CEO'))
            <!-- Allow viewing content and downloading -->
            <h3>Document Content:</h3>
            <pre>{{ Storage::get($document->file_path) }}</pre>
            <a href="{{ route('documents.download', $document->id) }}">Download Document</a>
        @else
            <p>You do not have permission to view/download this document.</p>
        @endif
    @else
        <!-- Document is public, view content -->
        <h3>Document Content:</h3>
        <pre>{{ Storage::get($document->file_path) }}</pre>
    @endif
@endsection

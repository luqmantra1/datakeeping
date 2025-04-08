@extends('filament::components.layouts.base')

@section('content')
    <div class="space-y-4">
        <!-- Document Title -->
        <x-filament::card>
            <x-slot name="header">
                <h3 class="text-xl font-semibold">Document: {{ $document->title }}</h3>
            </x-slot>
            <div class="space-y-2">
                <p><strong>Status:</strong> {{ ucfirst($document->status) }}</p>
                <p><strong>Uploaded By:</strong> {{ $document->uploader?->name ?? 'Unknown' }}</p>
                <p><strong>Approved By:</strong> {{ $document->approved_by ?? 'Not approved yet' }}</p>
                <p><strong>Encrypted:</strong> {{ $document->encrypted ? 'Yes' : 'No' }}</p>
                <p><strong>Description:</strong> {{ $document->description ?: 'No description provided.' }}</p>
                <p><strong>Uploaded On:</strong> {{ $document->created_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
            </div>
        </x-filament::card>

        <!-- Download Button -->
        <x-filament::card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold">Download Document</h3>
            </x-slot>
            <div class="space-y-2">
                @if($document->encrypted)
                    <p>The document is encrypted. Only authorized users (Admin or CEO) can download it.</p>
                @endif
                <a href="{{ url('/download/' . $document->id) }}" class="filament-button filament-button--primary">
                    Download Document
                </a>
            </div>
        </x-filament::card>
    </div>
@endsection

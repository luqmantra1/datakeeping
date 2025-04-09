<x-filament::page>
    <div>
        <h2>{{ $record->title }}</h2>
        <p>Uploaded by: {{ $record->uploader->name }}</p>
        <p>Status: {{ ucfirst($record->status) }}</p>

        @if ($documentUrl = $record->getDocumentContent())
            <div class="mt-4">
                @if (pathinfo($documentUrl, PATHINFO_EXTENSION) === 'pdf')
                    <iframe src="{{ $documentUrl }}" width="100%" height="600px"></iframe>
                @elseif (in_array(pathinfo($documentUrl, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
                    <img src="{{ $documentUrl }}" alt="Document Image" style="max-width: 100%; height: auto;">
                @else
                    <p>Unsupported file type</p>
                @endif
            </div>
        @else
            <p>No document content available to display.</p>
        @endif
    </div>
</x-filament::page>

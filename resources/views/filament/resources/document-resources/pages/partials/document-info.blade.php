<x-filament::page>
    <x-filament::card>
        <x-slot name="header">
            <h3 class="text-xl font-semibold">Document: {{ $record->title }}</h3>
        </x-slot>
        <div class="space-y-2">
            <p><strong>Status:</strong> {{ ucfirst($record->status) }}</p>
            <p><strong>Uploaded By:</strong> {{ $record->uploader?->name ?? 'Unknown' }}</p>
            <p><strong>Approved By:</strong> {{ $record->approved_by ?? 'Not approved yet' }}</p>
            <p><strong>Encrypted:</strong> {{ $record->encrypted ? 'Yes' : 'No' }}</p>
            <p><strong>Description:</strong> {{ $record->description ?: 'No description provided.' }}</p>
            <p><strong>Uploaded On:</strong> {{ $record->created_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
        </div>
    </x-filament::card>

    <x-filament::card>
        @if($record->encrypted)
            <p class="text-red-600">⚠️ This document is encrypted. Only Admin or CEO can download it.</p>
        @endif

        <a href="{{ url('/download/' . $record->id) }}" target="_blank" class="filament-button filament-button--primary">
            Download Document
        </a>
    </x-filament::card>
</x-filament::page>

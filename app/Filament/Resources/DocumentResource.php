<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->label('Document Title')
                ->placeholder('Enter document title')
                ->required()
                ->maxLength(255),

            FileUpload::make('file_path')
                ->label('Upload Document')
                ->directory('documents')
                ->preserveFilenames()
                ->required()
                ->helperText('Upload the document you want to store.'),

            Toggle::make('encrypted')
                ->label('Encrypt this file?')
                ->helperText('Toggle this if you want to encrypt the document.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('title')
                ->label('Document Title')
                ->searchable()
                ->sortable(),

            TextColumn::make('status')
                ->label('Status')
                ->sortable()
                ->getStateUsing(fn ($record) => ucfirst($record->status)),

            // Add column to display encryption status
            TextColumn::make('encrypted')
                ->label('Encrypted')
                ->getStateUsing(fn ($record) => $record->encrypted ? 'Yes' : 'No')
                ->sortable(),
        ])
        ->filters([
            SelectFilter::make('status')
                ->label('Filter by Status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
        ])
            ->actions([
                Actions\ViewAction::make(),

                // Edit and delete actions visible only for Admins and CEOs
                Actions\EditAction::make()
                    ->visible(fn () => Auth::user()?->hasAnyRole(['Admin', 'CEO'])),
                Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->hasAnyRole(['Admin', 'CEO'])),
                
                // Approval actions visible only for Admins and CEOs
                Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (Document $record) => $record->status === 'pending' && Auth::user()?->hasAnyRole(['Admin', 'CEO']))
                    ->requiresConfirmation()
                    ->action(fn (Document $record) => $record->update(['status' => 'approved'])),

                Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (Document $record) => $record->status === 'pending' && Auth::user()?->hasAnyRole(['Admin', 'CEO']))
                    ->requiresConfirmation()
                    ->action(fn (Document $record) => $record->update(['status' => 'rejected'])),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
            'view' => Pages\ViewDocument::route('/{record}/view'),
        ];
    }
}


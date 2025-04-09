<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions;
use Illuminate\Support\Facades\Auth;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationIcon = 'heroicon-o-document';

    // Define the form schema for document creation and editing
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
                ->required(),
        ]);
    }

    // Define the table schema for displaying documents
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
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                    })
                    ->sortable(),

                TextColumn::make('uploader.name')
                    ->label('Uploaded By')
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

                Actions\EditAction::make()
                    ->visible(fn () => Auth::user()?->hasAnyRole(['Admin', 'CEO'])),

                Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()?->hasAnyRole(['Admin', 'CEO'])),

                Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (Document $record) => $record->status === 'pending' && Auth::user()?->hasAnyRole(['Admin', 'CEO']))
                    ->requiresConfirmation()
                    ->action(function (Document $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => Auth::id(),
                        ]);
                    }),

                Actions\Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (Document $record) => $record->status === 'pending' && Auth::user()?->hasAnyRole(['Admin', 'CEO']))
                    ->requiresConfirmation()
                    ->action(function (Document $record) {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => Auth::id(),
                        ]);
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    // Define routes for pages related to the Document resource
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Models\Proposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;
    protected static ?string $navigationGroup = 'Internal';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // Define the form schema for proposal creation and editing
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'company_name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('proposal_title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('submission_date')
                    ->required(),

                Forms\Components\Textarea::make('request_details')
                    ->rows(4),

                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'waiting-quotation' => 'Waiting Quotation',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('draft'),
            ]);
    }

    // Define the table schema for displaying proposals
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')->label('Client'),
                Tables\Columns\TextColumn::make('proposal_title'),
                Tables\Columns\TextColumn::make('submission_date')->date(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'submitted',
                        'warning' => 'waiting-quotation',
                        'danger' => 'cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->filters([]) // Add any necessary filters here
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    // Define pages for the resource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProposalResource\Pages;
use App\Filament\Resources\ProposalResource\RelationManagers;
use App\Models\Proposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class ProposalResource extends Resource
{
    protected static ?string $model = Proposal::class;
    protected static ?string $navigationGroup = 'Sepakat Insurance Workflow';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProposals::route('/'),
            'create' => Pages\CreateProposal::route('/create'),
            'edit' => Pages\EditProposal::route('/{record}/edit'),
        ];
    }
}


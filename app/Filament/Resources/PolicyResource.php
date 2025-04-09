<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyResource\Pages;
use App\Models\Policy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;
    protected static ?string $navigationGroup = 'Internal';
    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    // Form definition for creating or editing policies
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('policy_number')
                    ->label('Policy Number')
                    ->disabled()
                    ->default('PO' . strtoupper(bin2hex(random_bytes(4)))) // Auto-generate policy number
                    ->required(),

                Forms\Components\Select::make('quotation_id')
                    ->label('Accepted Quotation')
                    ->relationship('quotation', 'quotation_number', fn ($query) => $query->where('acceptance_status', 'accepted'))
                    ->required(),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Start Date')
                    ->default(Carbon::now())  // Default to current date
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('End Date')
                    ->default(Carbon::now()->addYear())  // Default to one year from now
                    ->required(),

                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3),

                Forms\Components\Select::make('status')
                    ->label('Policy Status')
                    ->options([
                        'proposal' => 'Proposal',
                        'quotation' => 'Quotation',
                        'accepted' => 'Accepted',
                        'policy_generated' => 'Policy Generated',
                    ])
                    ->required(),
            ]);
    }

    // Table definition for displaying policies
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quotation.quotation_number')->label('Quotation'),
                Tables\Columns\TextColumn::make('policy_number')->label('Policy Number'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('Accept')
                    ->label('Accept Policy')
                    ->action(function (Policy $record) {
                        $record->update(['status' => 'accepted']);  // Update status to accepted
                        logAudit(
                            'Accept Policy',
                            "Policy #{$record->policy_number} was accepted.",
                            'Policy',
                            $record->id
                        );
                    })
                    ->visible(fn (Policy $record) => $record->status !== 'accepted'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }
}

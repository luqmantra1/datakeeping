<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationResource\Pages;
use App\Models\Quotation;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use App\Models\Policy;
use Carbon\Carbon;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;
    protected static ?string $navigationGroup = 'Internal';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('proposal_id')
                    ->label('Proposal')
                    ->relationship('proposal', 'proposal_title')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('insurance_company')
                    ->label('Insurance Company')
                    ->options([
                        'Allianz' => 'Allianz',
                        'Takaful' => 'Takaful',
                        'AIG' => 'AIG',
                        'Zurich' => 'Zurich',
                        'MSIG' => 'MSIG',
                        'Great Eastern' => 'Great Eastern',
                    ])
                    ->required()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Auto-generate quotation number based on insurance company
                        $prefix = strtoupper(substr($state, 0, 2)); // get first two letters
                        $randomNumber = rand(100000, 999999); // generate a random number
                        $quotationNumber = $prefix . $randomNumber; // combine prefix and random number

                        // Set the generated quotation number
                        $set('quotation_number', $quotationNumber);
                    }),

                Forms\Components\TextInput::make('quotation_number')
                    ->label('Quotation Number')
                    ->disabled(), // Disable editing, it will be auto-generated

                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('RM')
                    ->step(0.01),

                FileUpload::make('file_path')
                    ->label('Quotation File')
                    ->disk('public') // or your preferred disk
                    ->directory('quotations')
                    ->downloadable()
                    ->openable()
                    ->previewable(),

                Forms\Components\Select::make('status')
                    ->options([
                        'received' => 'Received',
                        'forwarded-to-client' => 'Forwarded to Client',
                        'rejected' => 'Rejected',
                    ])
                    ->default('received'),

                Forms\Components\Select::make('acceptance_status')
                    ->label('Acceptance Status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\Select::make('policy_status')
                    ->label('Policy Status')
                    ->options([
                        'pending' => 'Pending',
                        'generated' => 'Generated',
                    ])
                    ->disabled(fn ($record) => $record?->acceptance_status !== 'accepted')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('proposal.proposal_title')->label('Proposal'),
                Tables\Columns\TextColumn::make('insurance_company'),
                Tables\Columns\TextColumn::make('quotation_number'),
                Tables\Columns\TextColumn::make('amount')->money('myr'),
                Tables\Columns\BadgeColumn::make('status'),
                Tables\Columns\BadgeColumn::make('acceptance_status')
                    ->label('Acceptance Status')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                    ])
                    ->formatStateUsing(fn ($state) => $state ?? 'pending'),
                Tables\Columns\BadgeColumn::make('policy_status')
                    ->label('Policy Status')
                    ->colors([
                        'primary' => 'pending',
                        'success' => 'generated',
                    ])
                    ->formatStateUsing(fn ($state) => $state ?? 'pending'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
                Tables\Columns\TextColumn::make('policies.policy_number')
                    ->label('Generated Policy')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('accept')
                    ->label('Accept Quotation')
                    ->action(function ($record) {
                        // Log when the action is triggered
                        Log::info("Accepting quotation: {$record->quotation_number}");

                        // Update the quotation status to accepted
                        $record->update([
                            'acceptance_status' => 'accepted',
                            'policy_status' => 'pending',
                        ]);

                        // Log the updated record
                        Log::info("Updated Quotation: {$record->quotation_number} status changed to accepted.");
                    })
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject Quotation')
                    ->action(function ($record) {
                        // Log when the action is triggered
                        Log::info("Rejecting quotation: {$record->quotation_number}");

                        // Update the quotation status to rejected
                        $record->update([
                            'acceptance_status' => 'rejected',
                            'policy_status' => 'pending',
                        ]);

                        // Log the updated record
                        Log::info("Updated Quotation: {$record->quotation_number} status changed to rejected.");
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x-mark'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('generate-policy')
                    ->label('Generate Policy')
                    ->action(function ($record) {
                        // Log when the action is triggered
                        Log::info("Generating policy for quotation: {$record->quotation_number}");

                        // Generate a unique policy number (e.g., "POL-123456")
                        $policyNumber = 'POL-' . rand(100000, 999999);

                        // Create the policy
                        $policy = Policy::create([
                            'quotation_id' => $record->id,
                            'policy_number' => $policyNumber,
                            'start_date' => Carbon::now(),  // Today's date
                            'end_date' => Carbon::now()->addYear(),  // Add 1 year
                            'notes' => 'Policy generated from quotation ' . $record->quotation_number,
                            'file_path' => null,  // You can set this if there's a policy PDF
                        ]);

                        // Log the created policy
                        Log::info("Policy created: {$policy->policy_number} for quotation: {$record->quotation_number}");
                    })
                    ->color('success')
                    ->icon('heroicon-o-check'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
        ];
    }
}

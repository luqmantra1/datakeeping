<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PolicyResource\Pages;
use App\Models\Policy;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;
    protected static ?string $navigationGroup = 'Sepakat Insurance Workflow';
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    

    // Form definition for creating or editing policies
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('quotation_id')
                ->label('Accepted Quotation')
                ->relationship('quotation', 'quotation_number', fn ($query) => $query->where('acceptance_status', 'accepted'))
                ->required(),

            Forms\Components\TextInput::make('policy_number')
                ->label('Policy Number')
                ->disabled()  // Auto-generated field, so it's read-only
                ->required(),

            Forms\Components\DatePicker::make('start_date')
                ->label('Start Date')
                ->required(),

            Forms\Components\DatePicker::make('end_date')
                ->label('End Date')
                ->required(),

            Forms\Components\Textarea::make('notes')
                ->label('Notes')
                ->rows(3),

            Forms\Components\FileUpload::make('file_path')
                ->label('Policy Document')
                ->disk('public')
                ->directory('policies')
                ->downloadable()
                ->openable()
                ->previewable()
                ->required(),

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
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn ($state) => self::getProgressBar($state)) // This will now render the progress bar
                ->html(),  // Ensures raw HTML is rendered
            Tables\Columns\TextColumn::make('start_date')->date(),
            Tables\Columns\TextColumn::make('end_date')->date(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                
                //
            ])
            ->actions([
                // Edit and delete actions for each policy record
                Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Bulk delete action
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Define any relationships here if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            // Define routes for pages related to policies
            'index' => Pages\ListPolicies::route('/'),
            'create' => Pages\CreatePolicy::route('/create'),
            'edit' => Pages\EditPolicy::route('/{record}/edit'),
        ];
    }

    protected static function getProgressBar($status)
{
    $progress = 0;
    switch ($status) {
        case 'proposal':
            $progress = 25;
            break;
        case 'quotation':
            $progress = 50;
            break;
        case 'accepted':
            $progress = 75;
            break;
        case 'policy_generated':
            $progress = 100;
            break;
    }

    // Use Filament's ->html() to return raw HTML instead of plain text
    return "<div class='progress'><div class='progress-bar' role='progressbar' style='width: $progress%' aria-valuenow='$progress' aria-valuemin='0' aria-valuemax='100'>$progress%</div></div>";
}
    
}

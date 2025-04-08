<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            // Name
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            
            // Email
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            
            // Password
            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($state) => is_null($state)) // Only required if no password exists
                ->maxLength(255),
            
            // Roles (multiple roles allowed)
            Forms\Components\Select::make('roles')
                ->multiple()
                ->relationship('roles', 'name')
                ->preload()
                ->label('Assign Roles'),

            // Active status toggle
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            // Displaying user's name
            Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),

            // Displaying user's email
            Tables\Columns\TextColumn::make('email')
                ->sortable()
                ->searchable(),

            // Displaying assigned roles
            Tables\Columns\TextColumn::make('roles.name')
                ->label('Roles')
                ->sortable(),
        ])
        ->filters([
            // You can add filters if needed (e.g., active status)
            Tables\Filters\SelectFilter::make('is_active')
                ->options([
                    '1' => 'Active',
                    '0' => 'Inactive',
                ])
        ])
        ->actions([
            // Edit action
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            // Delete bulk action
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    
}

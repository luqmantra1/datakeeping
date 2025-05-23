<?php

// namespace App\Filament\Resources;

// use App\Filament\Resources\ProjectResource\Pages;
// use App\Filament\Resources\ProjectResource\RelationManagers;
// use App\Models\Project;
// use App\Models\User;
// use Filament\Forms;
// use Filament\Forms\Components\Select;
// use Filament\Forms\Components\Textarea;
// use Filament\Forms\Components\TextInput;
// use Filament\Forms\Form;
// use Filament\Resources\Resource;
// use Filament\Tables;
// use Filament\Tables\Columns\TextColumn;
// use Filament\Tables\Table;
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\SoftDeletingScope;

// class ProjectResource extends Resource
// {
//     protected static ?string $model = Project::class;

//     protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

//     public static function form(Form $form): Form
//     {
//         return $form->schema([
//             TextInput::make('title')->required(),
//             Textarea::make('description'),
//             Select::make('ceo_id')
//             ->label('CEO')
//             ->options(User::all()->pluck('name', 'id')) // Get all users and map name to id
//             ->required(), // Set it as required
//         ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 TextColumn::make('title')->sortable(),
//                 TextColumn::make('ceo.name')->label('CEO')->sortable(),
//                 TextColumn::make('created_at')->sortable(),
//             ])
//             ->filters([
//                 //
//             ])
//             ->actions([
//                 Tables\Actions\EditAction::make(),
//             ])
//             ->bulkActions([
//                 Tables\Actions\BulkActionGroup::make([
//                     Tables\Actions\DeleteBulkAction::make(),
//                 ]),
//             ]);
//     }

//     public static function getRelations(): array
//     {
//         return [
//             //
//         ];
//     }

//     public static function getPages(): array
//     {
//         return [
//             'index' => Pages\ListProjects::route('/'),
//             'create' => Pages\CreateProject::route('/create'),
//             'edit' => Pages\EditProject::route('/{record}/edit'),
//         ];
//     }
// }

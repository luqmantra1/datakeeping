<?php

// namespace App\Filament\Resources;

// use App\Filament\Resources\TaskResource\Pages;
// use App\Filament\Resources\TaskResource\RelationManagers;
// use App\Models\Task;
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

// class TaskResource extends Resource
// {
//     protected static ?string $model = Task::class;

//     protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

//     public static function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 Select::make('user_id')  // Field name that will store the selected user ID
//                     ->label('Assign to User')  // Label for the field
//                     ->options(User::all()->pluck('name', 'id'))  // Fetch all users and use their ID as the value and name as the label
//                     ->required(),  // If you want this field to be required
//                 TextInput::make('title')->required(),
//                 Textarea::make('description'),
//                 Select::make('assigned_to')->relationship('users', 'name')->required(),
//                 Select::make('status')->options([
//                     'pending' => 'Pending',
//                     'in_progress' => 'In Progress',
//                     'completed' => 'Completed',
//                 ])->default('pending'),
                
//             ]);
//     }

//     public static function table(Table $table): Table
//     {
//         return $table
//             ->columns([
//                 TextColumn::make('title')->sortable(),
//                 TextColumn::make('assigned_to.name')->label('Assigned To')->sortable(),
//                 TextColumn::make('status')->sortable(),
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
//             'index' => Pages\ListTasks::route('/'),
//             'create' => Pages\CreateTask::route('/create'),
//             'edit' => Pages\EditTask::route('/{record}/edit'),
//         ];
//     }
// }

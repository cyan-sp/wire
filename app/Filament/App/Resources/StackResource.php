<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\StackResource\Pages;
use App\Filament\App\Resources\StackResource\RelationManagers;
use App\Models\Stack;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StackResource extends Resource
{
    // Tell Filament which model this resource manages
    protected static ?string $model = Stack::class;

    // Set up navigation and organization, similar to CouponResource
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $navigationGroup = 'Plan Management';

    // Define the form for creating and editing stacks
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Card limit input
                Forms\Components\TextInput::make('card_limit')
                    ->label('Card Limit')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Maximum number of cards that can be issued from this stack'),

                // Cards used counter (readonly in form)
                Forms\Components\TextInput::make('cards_used')
                    ->label('Cards Used')
                    ->numeric()
                    ->disabled()
                    ->default(0)
                    ->helperText('Number of cards already issued from this stack'),

                // Active status toggle
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this stack can issue new cards'),

                // Plan relationship - Note we only allow one plan per stack
                Forms\Components\Select::make('plan')
                    ->label('Associated Plan')
                    ->relationship('plan', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->helperText('Plan that will use this card stack'),
            ]);
    }

    // Define the table for listing stacks
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Show which plan owns this stack
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')
                    ->sortable()
                    ->searchable(),

                // Show card limits and usage
                Tables\Columns\TextColumn::make('card_limit')
                    ->label('Total Limit')
                    ->sortable(),

                    Tables\Columns\TextColumn::make('cards_used')
                    ->label('Used')
                    ->sortable(),
                
                // Shows remaining cards with colored indicator
                // Tables\Columns\ProgressColumn::make('usage')
                //     ->label('Usage')
                //     ->progress(fn (Stack $record): float => 
                //                $record->card_limit > 0 
                //                ? ($record->cards_used / $record->card_limit) * 100 
                //                : 0
                //     ),

                // Show stack status with icon
                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),

                // Show available cards with color coding
                Tables\Columns\BadgeColumn::make('available_cards')
                    ->label('Available')
                    ->colors([
                        'danger' => fn ($state): bool => $state === 0,
                        'warning' => fn ($state): bool => $state < 10,
                        'success' => fn ($state): bool => $state >= 10,
                    ])
                    ->formatStateUsing(fn ($state): string => number_format($state)),
            ])
            ->filters([
                // Add filter for active/inactive stacks
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStacks::route('/'),
            'create' => Pages\CreateStack::route('/create'),
            'edit' => Pages\EditStack::route('/{record}/edit'),
        ];
    }
}

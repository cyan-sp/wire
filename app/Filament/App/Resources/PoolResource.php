<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\PoolResource\Pages;
use App\Models\Pool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PoolResource extends Resource
{
    // Define the model this resource manages
    protected static ?string $model = Pool::class;
    
    // Set up navigation and organization
    protected static ?string $navigationIcon = 'heroicon-o-ticket'; // Using a ticket icon since pools manage coupon creation
    protected static ?string $navigationGroup = 'Plan Management'; // Grouping with related features

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Basic pool information
                Forms\Components\TextInput::make('coupon_limit')
                    ->label('Coupon Limit')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->helperText('Maximum number of coupons that can be created from this pool'),

                Forms\Components\DateTimePicker::make('starts_at')
                    ->label('Start Date')
                    ->required()
                    ->default(now())
                    ->helperText('When this pool becomes available for use'),

                Forms\Components\DateTimePicker::make('expires_at')
                    ->label('Expiration Date')
                    ->required()
                    ->after('starts_at') // Ensures expiration is after start date
                    ->helperText('When this pool expires and can no longer be used'),

                // Plan relationship - Note we only allow one plan per pool as per requirements
                Forms\Components\Select::make('plan')
                    ->label('Associated Plan')
                    ->relationship('plan', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->helperText('Plan that will use this coupon pool'),

                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->default(true)
                    ->helperText('Whether this pool is currently active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('plan.name')
                    ->label('Plan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('coupon_limit')
                    ->label('Total Limit')
                    ->sortable(),

                Tables\Columns\TextColumn::make('coupons_used')
                    ->label('Used')
                    ->sortable(),
                
                // Shows remaining coupons as a progress indicator
                // Tables\Columns\ProgressColumn::make('usage')
                //     ->label('Usage')
                //     ->progress(fn (Pool $record): float => 
                //         $record->coupon_limit > 0 
                //             ? ($record->coupons_used / $record->coupon_limit) * 100 
                //             : 0
                //     ),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Starts')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                // Add filters for active/inactive pools
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
                // Add date range filter
                Tables\Filters\Filter::make('active_period')
                    ->form([
                        Forms\Components\DatePicker::make('starts_from'),
                        Forms\Components\DatePicker::make('starts_until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['starts_from'], 
                                fn($q, $date) => $q->where('starts_at', '>=', $date))
                            ->when($data['starts_until'], 
                                fn($q, $date) => $q->where('starts_at', '<=', $date));
                    })
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
            // You could add relations here to show coupons created from this pool
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPools::route('/'),
            'create' => Pages\CreatePool::route('/create'),
            'edit' => Pages\EditPool::route('/{record}/edit'),
        ];
    }
}

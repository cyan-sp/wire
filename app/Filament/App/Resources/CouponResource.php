<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\CouponResource\Pages;
//use App\Filament\App\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Marketing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Code')
                    ->required()
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'offer' => 'Offer',
                        'promo' => 'Promo',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->nullable(),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('End Date')
                    ->required(),

                Forms\Components\TextInput::make('redeem_at')
                    ->label('Redeem At')
                    ->nullable(),

                Forms\Components\FileUpload::make('image')
                    ->label('Image')
                    ->nullable(),

                // Plans relationship
                Forms\Components\Select::make('plans')
                    ->label('Associated Plans')
                    ->multiple()
                    ->relationship('plans', 'name') // Assuming 'name' is the column in the `plans` table
                    ->preload()
                    ->required(),
            ]);
    }

    // App/Filament/App/Resources/CouponResource.php

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Code')->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'success' => 'offer',
                        'primary' => 'promo',
                    ]),
                Tables\Columns\TextColumn::make('name')->label('Name')->sortable(),
                Tables\Columns\BadgeColumn::make('available_coupons')
                    ->label('Available')
                    ->colors([
                        'danger' => fn ($state): bool => $state === 0,
                        'warning' => fn ($state): bool => $state < 10,
                        'success' => fn ($state): bool => $state >= 10,
                    ])
                    ->formatStateUsing(fn ($state): string => number_format($state)),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TagsColumn::make('plans.name')
                    ->label('Plans'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add more relationships if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}

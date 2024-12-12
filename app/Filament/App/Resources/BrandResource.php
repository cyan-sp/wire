<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BrandResource\Pages;
//use App\Filament\App\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Brand Name')
                        ->required()
                        ->placeholder('Enter brand name')
                        ->maxLength(255),
//                    Forms\Components\Select::make('company_id')
//                        ->label('Company')
//                        ->relationship('company', 'company_name') // Assuming the `company_name` field exists
//                        ->searchable()
//                        ->placeholder('Select a company'),
                ])->columns(2), // Adjust the number of columns in the card layout
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('name')
                        ->weight(FontWeight::Bold)
                        ->color('primary')
                        ->size('lg'), // Adjust size for better emphasis
                    Tables\Columns\TextColumn::make('company.company_name')
                        ->label('Company')
                        ->color('gray')
                        ->limit(30),
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 2,
                'lg' => 3, // Number of cards per row on larger screens
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}

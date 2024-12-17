<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

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
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('logo')
                        ->label('Logo')
                        ->height('100px')
                        ->width('100px')
                        ->rounded(),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->label('Brand Name')
                            ->weight(FontWeight::Bold)
                            ->color('primary'),
                        Tables\Columns\TextColumn::make('company.company_name')
                            ->label('Company')
                            ->color('gray')
                            ->limit(30),
                    ]),
                ])->space(3),
            ])
            ->contentGrid([
                'md' => 2, // 2 cards per row on medium screens
                'lg' => 3, // 3 cards per row on large screens
                'xl' => 4, // 4 cards per row on extra-large screens
            ])
            ->paginated([
                12, 24, 48, 'all', // Pagination options
            ])
            // ->actions([
            //     Tables\Actions\Action::make('view')
            //         ->label('View Brand')
            //         ->icon('heroicon-m-eye')
            //         ->url(fn (Brand $record): string => route('brands.view', ['brand' => $record->id])),
            //     Tables\Actions\EditAction::make(),
            // ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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


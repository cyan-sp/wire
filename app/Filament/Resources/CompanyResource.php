<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
//use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('legal_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tax_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->maxLength(20),
                Forms\Components\Textarea::make('address')
                    ->maxLength(500),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('website')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->maxLength(100),
                Forms\Components\TextInput::make('state')
                    ->maxLength(100),
                Forms\Components\TextInput::make('country')
                    ->maxLength(100),
                Forms\Components\Toggle::make('status')
                    ->default(true),
                Forms\Components\FileUpload::make('logo')
                    ->directory('logos')
                    ->image(),
//                Forms\Components\Select::make('brand_id')
//                    ->relationship('brand', 'name') // Assuming the Brand model has a `name` attribute
//                    ->searchable()
//                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\ToggleColumn::make('status'),
                Tables\Columns\ImageColumn::make('logo'),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}

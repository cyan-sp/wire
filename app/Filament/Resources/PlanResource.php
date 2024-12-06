<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
//use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(3),
                Forms\Components\TextInput::make('description')
                    ->required(),
                Forms\Components\TextInput::make('prefix')
                    ->required()
                    ->maxLength(3),
                Forms\Components\Toggle::make('status')
                    ->default(true),
                Forms\Components\TextInput::make('consecutive_length')
                    ->numeric()
//                    ->min(5)
//                    ->max(10)
                    ->default(5),
                Forms\Components\ColorPicker::make('color')
                    ->nullable(),
                Forms\Components\FileUpload::make('image')
                    ->directory('plan-images')
                    ->nullable(),
                Forms\Components\TextInput::make('current_sequence')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('prefix'),
                Tables\Columns\ToggleColumn::make('status'),
                Tables\Columns\TextColumn::make('consecutive_length'),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('current_sequence'),
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}

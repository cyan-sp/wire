<?php

namespace App\Filament\App\Pages\Tenancy;

use App\Models\Plan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterPlan extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Plan';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Plan Details')->schema([
                        TextInput::make('code')
                            ->label('Plan Code')
                            ->maxLength(3)
                            ->required()
                            ->placeholder('E.g., 001'),

                        TextInput::make('name')
                            ->label('name')
                            ->required(),

                        TextInput::make('prefix')
                            ->label('Prefix')
                            ->maxLength(3)
                            ->required()
                            ->placeholder('E.g., 444'),

                        TextInput::make('consecutive_length')
                            ->label('Consecutive Length')
                            ->numeric()
                            // ->min(5)
                            // ->max(10)
                            ->default(5)
                            ->required(),

                        TextInput::make('color')
                            ->label('Color')
                            ->required()
                            ->placeholder('E.g., #ff5733'),

                        TextInput::make('image')
                            ->label('Image Path')
                            ->placeholder('E.g., images/plan.png'),
                    ]),
                ]),
            ]);
    }

    protected function handleRegistration(array $data): Plan
    {
        // Create the plan
        $plan = Plan::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'prefix' => $data['prefix'],
            'consecutive_length' => $data['consecutive_length'],
            'color' => $data['color'],
            'image' => $data['image'],
            'status' => true,
            'current_sequence' => 0,
        ]);

        // Attach the authenticated user to the plan
        $plan->members()->attach(auth()->id());

        return $plan;
    }
}

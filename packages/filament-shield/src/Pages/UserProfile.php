<?php

namespace BezhanSalleh\FilamentShield\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Actions\ButtonAction;
use Filament\Pages\Contracts\HasFormActions;
use Filament\Pages\Page;
use Filament\Resources\Pages\Concerns\UsesResourceForm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;

class UserProfile extends Page implements HasFormActions
{
    use UsesResourceForm;
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament-shield::pages.user-profile';

    protected function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Profile',
        ];
    }

    public function mount(): void
    {
        $this->form->fill([
            'name' => $this->getFormModel()->name,
            'email' => $this->getFormModel()->email,
        ]);
    }

    protected function getFormModel(): Model|string|null
    {
        return Filament::auth()->user();
    }

    public function save(): void
    {
        $this->getFormModel()->update($this->form->getState());

        $this->notify('success', strval(__('filament::resources/pages/edit-record.messages.saved')));
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('General')
            ->columns(2)
            ->schema([
                TextInput::make('name')->required()->label(__('filament-shield::filament-shield.labels.users.name')),
                TextInput::make('email')->required()->label(__('filament-shield::filament-shield.labels.users.email')),
            ]),
            Section::make('Update Password')
                ->columns(2)
                ->schema([
                    TextInput::make('current_password')
                        ->label(__('filament-shield::filament-shield.labels.users.password_current'))
                        ->password()
                        ->rules(['required_with:new_password'])
                        ->currentPassword()
                        ->autocomplete('off')
                        ->columnSpan(1),
                    Grid::make()
                        ->schema([
                            TextInput::make('new_password')
                                ->label(__('filament-shield::filament-shield.labels.users.password_new'))
                                ->rules(['confirmed', Password::defaults()])
                                ->autocomplete('new-password'),
                            TextInput::make('new_password_confirmation')
                                ->label(__('filament-shield::filament-shield.labels.users.password_confirm'))
                                ->password()
                                ->rules([
                                    'required_with:new_password',
                                ])
                                ->autocomplete('new-password'),
                        ]),
                ]),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            ButtonAction::make('save')
                ->label(__('filament-shield::filament-shield.page.save'))
                ->submit(),
        ];
    }
}

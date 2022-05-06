<?php

namespace BezhanSalleh\FilamentShield;

use BezhanSalleh\FilamentShield\Filament\Resources\RoleResource;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelPackageTools\Package;

class FilamentShieldServiceProvider extends PluginServiceProvider
{
    protected function getResources(): array
    {
        return [
            RoleResource::class,
        ];
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-shield')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasCommands($this->getCommands());
    }

    public function packageBooted(): void
    {
        if (config('filament-shield.register_role_policy')) {
            Gate::policy('Spatie\Permission\Models\Role', 'App\Policies\RolePolicy');
        }
    }

    protected function getCommands(): array
    {
        return [
            //Commands\MakeCreateShieldCommand::class,
            //Commands\MakeInstallShieldCommand::class,
            //Commands\MakePublishShieldCommand::class,
            //Commands\MakeUpgradeShieldCommand::class,
            Commands\MakeGenerateShieldCommand::class,
            Commands\MakeSuperAdminShieldCommand::class,
        ];
    }
}

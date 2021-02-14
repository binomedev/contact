<?php

namespace Binomedev\Contact;

use Illuminate\Contracts\Support\DeferrableProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ContactServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('contact')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_contact_table')
            ->hasRoute('web')
            ->hasTranslations()//->hasCommand(ContactCommand::class)
        ;
    }

    public function packageRegistered()
    {
        $this->app->singleton(Contact::class);
    }

    public function provides()
    {
        return [Contact::class];
    }
}

<?php

namespace Binomedev\Contact;

use Binomedev\Contact\Commands\ContactCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ContactServiceProvider extends PackageServiceProvider
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
            ->hasTranslations()
            //->hasCommand(ContactCommand::class)
        ;
    }
}

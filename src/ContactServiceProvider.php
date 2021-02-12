<?php

namespace Binomedev\Contact;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Binomedev\Contact\Commands\ContactCommand;

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

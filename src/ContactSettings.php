<?php


namespace Binomedev\Contact;


use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use OptimistDigital\NovaSettings\NovaSettings;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class ContactSettings
{
    public static function boot()
    {

        $casts = [
            'socials' => FlexibleCast::class,
            'emails' => FlexibleCast::class,
            'numbers' => FlexibleCast::class,
            'addresses' => FlexibleCast::class,
        ];

        NovaSettings::addSettingsFields([
            static::genericFields(),
            static::socialFields(),
        ], $casts, 'Contact');
    }

    private static function genericFields()
    {
        return new Panel('Contact Information', [
            Flexible::make('Emails')->addLayout('Contact Email', 'contact_email', [
                Text::make('Email Address')->rules('email', 'required'),
                Boolean::make('Is Primary')->default(false),
            ])->button('Add Email'),

            Flexible::make('Numbers')->addLayout('Contact Number', 'contact_number', [
                Text::make('Phone Number')->required(),
                Boolean::make('Is Primary')->default(false),
            ])->button('Add Number'),

            Flexible::make('Addresses')->addLayout('Address', 'address', [
                Textarea::make('Address')->required(),
                Boolean::make('Is Primary')->default(false),
            ])->button('Add Address'),
        ]);
    }

    private static function socialFields()
    {
        return new Panel('Social Media', [
            Flexible::make('Socials')->addLayout('Social Media', 'social', [
                Text::make('Name')->required(),
                Text::make('Icon')->nullable(),
                Text::make('Url')->required()->rules('url')
            ])->button('Add Social'),
        ]);
    }
}

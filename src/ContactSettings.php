<?php


namespace Binomedev\Contact;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use OptimistDigital\NovaSettings\NovaSettings;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class ContactSettings extends Collection
{
    use HasFlexible;

    /**
     * @var array
     */
    private static $casts = [
        'socials' => FlexibleCast::class,
        'emails' => FlexibleCast::class,
        'numbers' => FlexibleCast::class,
        'addresses' => FlexibleCast::class,
    ];

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
                Text::make('Url')->required()->rules('url'),
            ])->button('Add Social'),
        ]);
    }

    /**
     * Settings constructor.
     * Disable the normal construct and initialise it using the nova settings.
     */
    public function __construct()
    {
        $items = $this->solveFlexibleCasts(nova_get_settings());
        parent::__construct($items);
    }

    /**
     * Returns an array with the correct casted values.
     * We have to do this because NovaSettings doesn't fully support FlexibleCast.
     * @param array $settings
     * @return Collection
     */
    private function solveFlexibleCasts(array $settings): Collection
    {
        return collect($settings)->map(function ($setting, $name) {
            return $this->isFlexible($name)
                // Cast the value to flexible. We have to do this because NovaSettings casts it only when it saves it to the database and not when retrieving it.
                ? $this->toFlexible($setting)
                // use the default casting done by the NovaSettings.
                : $setting;
        });
    }

    /**
     * Determines if the setting is in the casts array and is set to FlexibleCast.
     *
     * @param $settingName
     * @return bool
     */
    private function isFlexible($settingName): bool
    {
        $casts = static::$casts;

        return array_key_exists($settingName, $casts) && $casts[$settingName] === FlexibleCast::class;
    }

    /**
     * Returns the primary value from a flexible setting
     *
     * @param string $flexibleSettingKey The flexible setting that holds the layouts
     * @param string $attributeName The attribute value to be retrieved. Default 'value'
     * @param string $primaryKey The key that determines if the layout is primary. Default 'is_primary'
     * @return mixed|null
     * @throws \Exception
     */
    public function primary(string $flexibleSettingKey, string $attributeName = 'value', $primaryKey = 'is_primary')
    {
        if (! $this->isFlexible($flexibleSettingKey)) {
            throw new \Exception("Your are trying to access the setting '{$flexibleSettingKey}' which is not casted as flexible.");
        }

        $setting = $this->get($flexibleSettingKey);

        if (is_null($setting)) {
            return null;
        }

        $value = $setting->filter(function ($item) use ($primaryKey) {
            return $item[$primaryKey];
        });

        if ($value->isEmpty()) {
            $value = $setting;
        }

        return optional($value->first())->{$attributeName};
    }
}

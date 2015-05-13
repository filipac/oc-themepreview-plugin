<?php namespace Filipac\ThemePreview;

use Backend\Facades\BackendAuth;
use Filipac\ThemePreview\Models\Settings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;

/**
 * ThemePreview Plugin Information File
 */
class Plugin extends PluginBase
{

    protected $deferred = true;


    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'ThemePreview',
            'description' => 'No description provided yet...',
            'author'      => 'filipac',
            'icon'        => 'icon-leaf'
        ];
    }


    public function boot()
    {
        $activeThemeFn               = function ($app) {
            $active     = Settings::get('active', '0');
            $permission = Settings::get('permission', 'cms.manage_themes');
            if ($active == '0') {
                return;
            }
            $theme = Settings::get('theme', '');
            return strlen($theme) > 0 && BackendAuth::getUser() !== null && BackendAuth::getUser()->hasAccess($permission) ? $theme : null;
        };
        app()['filipac.activetheme'] = $activeThemeFn;
        \Event::listen('cms.activeTheme', function () {
            return app('filipac.activetheme');
        });
    }


    public function registerPermissions()
    {
        return [
            'filipac.themepreview.manage_settings' => [
                'label' => 'Manage the theme preview settings',
                'tab'   => 'cms::lang.permissions.name'
            ]
        ];
    }


    public function registerSettings()
    {
        return [
            'settings_themepreview' => [
                'label'       => 'Theme preview',
                'description' => 'Preview a theme before publishing',
                'category'    => SettingsManager::CATEGORY_CMS,
                'icon'        => 'icon-cog',
                'class'       => 'filipac\ThemePreview\Models\Settings',
                'order'       => 500,
                'permissions' => [ 'filipac.themepreview.manage_settings' ],
                'keywords'    => 'security location'
            ]
        ];
    }

}

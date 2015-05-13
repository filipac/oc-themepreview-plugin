<?php namespace Filipac\ThemePreview\Models;

use Cms\Classes\Theme;
use Model;

/**
 * Settings Model
 */
class Settings extends Model
{

    public $implement = [ 'System.Behaviors.SettingsModel' ];

    public $settingsCode = 'filipac_themepreview_settings';

    public $requiredPermissions = ['acme.blog.access_posts'];

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->permission = 'cms.manage_themes';
    }

    public function getThemeOptions($keyValue = null)
    {
        $all = Theme::all();
        $ret = [];
        foreach($all as $theme) {
            $ret[$theme->getDirName()] = $theme->getConfigValue('name', $theme->getDirName());
        }
        return ['' => '-- none --'] + $ret;
    }


    public function getPermissionOptions($keyValue = null){
        $all = \BackendAuth::listPermissions();
        $ret = [];
        foreach($all as $permission) {
            $ret[$permission->code] = $permission->code;
        }
        return $ret;
    }

}
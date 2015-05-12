<?php
    /**
     * @package   Hippo Shortcode
     * @author    ThemeHippo http://www.themehippo.com
     * @copyright Copyright (c) 2013 - 2014 ThemeHippo
     * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
     */
    //no direct accees
    defined('_JEXEC') or die('resticted aceess');

    jimport('joomla.filesystem.file');
    jimport('joomla.filesystem.folder');

    class HippoShortcode
    {

        private static $_instance;
        private static $shortcode_tags = array();
        private $importedFiles = array();
        private $shortcodeDirectory = 'hippo-shortcodes';

        private function __construct()
        {

        }

        public static function getShortcodes()
        {
            return self::getInstance()->shortcode_tags;
        }

        final public static function getInstance()
        {
            if (!self::$_instance) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public static function importShortCodeFiles()
        {

            $shortcodes = array();

            $themeshortcodes  = glob(self::getInstance()->getTemplatePath() . '/' . self::getInstance()->shortcodeDirectory . '/*.php');
            $pluginshortcodes = glob(self::getInstance()->getPluginPath() . '/' . self::getInstance()->shortcodeDirectory . '/*.php');

            foreach ((array) $themeshortcodes as $value)
                $shortcodes[ ] = basename($value);
            foreach ((array) $pluginshortcodes as $value)
                $shortcodes[ ] = basename($value);

            $shortcodes = array_unique($shortcodes);

            self::getInstance()->Import('core/wp_shortcodes.php', self::getInstance());

            foreach ($shortcodes as $shortcode)
                self::getInstance()->Import(self::getInstance()->shortcodeDirectory . '/' . $shortcode);

            return self::getInstance();
        }

        public static function getTemplatePath($base = FALSE)
        {
            if ($base == TRUE)
                return JURI::root(TRUE) . '/templates/' . self::getInstance()->getTemplate();

            return JPATH_THEMES . '/' . self::getInstance()->getTemplate();
        }

        public static function getTemplate()
        {
            //return self::getInstance()->getDocument()->template;
            return JFactory::getApplication()->getTemplate();
        }

        public static function getPluginPath($base = FALSE)
        {
            if ($base == TRUE)
                return JURI::root(TRUE) . '/plugins/system/hipposhortcode';

            return JPATH_PLUGINS . '/system/hipposhortcode';
        }

        public static function Import($paths, $thisobj = FALSE)
        {
            if (is_array($paths))
                foreach ((array) $paths as $file)
                    self::_Import($file, $thisobj);
            else self::_Import($paths, $thisobj);
            return self::getInstance();
        }

        private static function _Import($path, $thisobj)
        {
            $intheme  = self::getInstance()->getTemplatePath() . '/' . $path;
            $inplugin = self::getInstance()->getPluginPath() . '/' . $path;

            if (file_exists($intheme) && !is_dir($intheme)) {
                self::getInstance()->importedFiles[ ] = $intheme;
                require_once $intheme;
            } elseif (file_exists($inplugin) && !is_dir($inplugin)) {
                self::getInstance()->importedFiles[ ] = $inplugin;
                require_once $inplugin;
            }
            return self::getInstance();
        }

        public static function slug($text)
        {
            return preg_replace('/[^a-z0-9_]/i', '-', strtolower($text));
        }

        public static function strip_shortcode($text, $tags = '', $invert = FALSE)
        {
            self::getInstance()->Import('core/wp_shortcodes.php', self::getInstance());
            return strip_shortcodes($text); 
        }
    }
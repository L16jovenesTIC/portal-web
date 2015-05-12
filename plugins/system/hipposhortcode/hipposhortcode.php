<?php
    /**
     * @package   Hippo Shortcode
     * @author    ThemeHippo http://www.themehippo.com
     * @copyright Copyright (c) 2013 - 2014 ThemeHippo
     * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
     */

    //no direct accees
    defined('_JEXEC') or die ('resticted aceess');

    jimport('joomla.event.plugin');

    class  plgSystemHippoShortcode extends JPlugin
    {
        public function onAfterInitialise()
        {
            $plugin_core_path = JPATH_PLUGINS . '/system/hipposhortcode/helper.php';
            if (file_exists($plugin_core_path)) {
                require_once($plugin_core_path);
                HippoShortcode::getInstance();
            }
        }

        public function onAfterRender()
        {
            if (!JFactory::getApplication()->isAdmin()) {
	            

                $document = JFactory::getDocument();
                $type     = $document->getType();

                if ($type == 'html') {

                    //$data = JResponse::getBody();  // old method and deprecated.

	                $data = JFactory::getApplication()->getBody();
                    HippoShortcode::getInstance()->importShortCodeFiles();

                    $data = shortcode_unautop($data);
                    $data = do_shortcode($data);

                     //JResponse::setBody($data);   // old method and deprecated.
	                JFactory::getApplication()->setBody($data);
                }
            }
            return TRUE;
        }

        public function onContentPrepare($context, &$article, &$params, $page = 0)
        {
            // Don't run this plugin when the content is being indexed
            if ($context == 'com_finder.indexer' or $context == 'com_search.search') {
                $article->text = HippoShortcode::getInstance()->strip_shortcode($article->text);
            }

        }
    }
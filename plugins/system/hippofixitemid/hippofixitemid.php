<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.hippofixitemid
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class plgSystemHippoFixItemID extends JPlugin
{
        /**
         * @return  void
         */
        public function onAfterInitialise()
        {
                $app = JFactory::getApplication();

                // Get the router
                $router = $app->getRouter();

                // Create a callback array to call the replaceRoute method of this object
                $replaceRouteCallback = array($this, 'replaceRoute');

                // Attach the callback to the router
                $router->attachBuildRule($replaceRouteCallback);
        }

        /**
         * @param   JRouterSite  &$router  The Joomla Site Router
         * @param   JURI         &$uri     The URI to parse
         *
         * @return  array  The array of processed URI variables
         */
        public function replaceRoute(&$router, &$uri)
        {


                $Itemid = $uri->getVar('Itemid');
                $id = $uri->getVar('id');
                $option = $uri->getVar('option');
                $view = $uri->getVar('view');

                $item = JFactory::getApplication()->getMenu()->getItem($Itemid);

                $paramID = array();
                $paramID[] = (int) $this->params->get('removemenuitem1',0);
                $paramID[] = (int) $this->params->get('removemenuitem2',0);
                $paramID[] = (int) $this->params->get('removemenuitem3',0);
                $paramID[] = (int) $this->params->get('removemenuitem4',0);
                $paramID[] = (int) $this->params->get('removemenuitem5',0);

                if( in_array($Itemid, $paramID) && $option=='com_content' && $view=='article'){

                        $uri->delVar('Itemid');
                }
        }
}
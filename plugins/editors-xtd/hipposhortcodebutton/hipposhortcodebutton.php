<?php
	/**
	 * @package   Hippo Shortcode Button
	 * @author    ThemeHippo http://www.themehippo.com
	 * @copyright Copyright (c) 2013 - 2014 ThemeHippo
	 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
	 */

	//no direct accees
	defined( '_JEXEC' ) or die ( 'resticted aceess' );


	class plgButtonHippoShortcodeButton extends JPlugin {

		public function onDisplay( $name ) {

			$link = $this->getLink();

			$button        = new JObject;
			$button->modal = TRUE;
			$button->link  = $link;
			$button->class = 'btn';
			$button->text  = 'Hippo Shortcodes';
			$button->name  = 'blank';
			if ( JVERSION > 3 ) {
				$button->name = 'briefcase';
			}

			$button->options = "{handler: 'iframe', size: {x: 800, y: 600}}";

			return $button;
		}


		private function getCurrentTemplate(){

			// $db->setQuery( 'SELECT id,template FROM #__template_styles WHERE client_id=0 AND home=1');

			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id', 'template')));
			$query->from($db->quoteName('#__template_styles'));
			$query->where($db->quoteName('client_id'). ' = ' . $db->quote('0'). '
			AND ' . $db->quoteName('home').' = '.$db->quote('1'));
			$db->setQuery($query);

			return $db->loadObject();
		}

		/**
		 * @return string
		 */
		private function getLink() {

			$templatename = $this->getCurrentTemplate();
			$template = $templatename->template;

			return  '../plugins/editors-xtd/hipposhortcodebutton/popup/index.php?template='.$template.'&path='.JPATH_ROOT.'&uri='.JURI::root( true );
		}

	}


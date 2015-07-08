<?php
 
/**
 *
 */
class JoomlaInstallerScript
{
	/**
	 * @param $type
	 * @param $parent
	 *
	 * @return bool
	 */
	public function preflight($type, $parent)
	{
		JError::raiseWarning(100, 'The Hippo QuickStart package should not be installed into an existing Joomla instance. It is a stand-alone Joomla installation.');
		return false;
	}
}
<?php
/**
 * @package         Modules Anywhere
 * @version         5.0.0PRO
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class PlgSystemModulesAnywhereInstallerScript extends PlgSystemModulesAnywhereInstallerScriptHelper
{
	public $name           = 'MODULES_ANYWHERE';
	public $alias          = 'modulesanywhere';
	public $extension_type = 'plugin';

	public function uninstall($adapter)
	{
		$this->uninstallPlugin($this->extname, 'editors-xtd');
	}
}

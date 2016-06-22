<?php
/**
 * @package         Tooltips
 * @version         5.0.0PRO
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            http://www.regularlabs.com
 * @copyright       Copyright © 2016 Regular Labs All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die;

/**
 ** Plugin that places the button
 */
class PlgButtonTooltipsHelper
{
	public function __construct(&$params)
	{
		$this->params = $params;
	}

	/**
	 * Display the button
	 *
	 * @return array A two element array of ( imageName, textToInsert )
	 */
	function render($name)
	{
		$button = new JObject;

		if (JFactory::getUser()->get('guest'))
		{
			return $button;
		}

		if (JFactory::getApplication()->isSite() && !$this->params->enable_frontend)
		{
			return $button;
		}

		if ($this->params->button_use_simple_button)
		{
			return $this->renderSimpleButton($name);
		}

		return $this->renderButton($name);
	}

	private function renderButton($name)
	{
		require_once JPATH_LIBRARIES . '/regularlabs/helpers/functions.php';

		RLFunctions::stylesheet('regularlabs/style.min.css', '16.4.13421');

		$button = new JObject;

		$icon = 'reglab icon-tooltips';
		$link = 'index.php?rl_qp=1'
			. '&folder=plugins.editors-xtd.tooltips'
			. '&file=popup.php'
			. '&name=' . $name;

		$button->modal   = true;
		$button->class   = 'btn';
		$button->link    = $link;
		$button->text    = $this->getButtonText();
		$button->name    = $icon;
		$button->options = "{handler: 'iframe', size: {x:Math.min(window.getSize().x-100, 620), y:Math.min(window.getSize().y-100, 790)}}";

		return $button;
	}

	private function renderSimpleButton($name)
	{
		require_once JPATH_LIBRARIES . '/regularlabs/helpers/functions.php';

		RLFunctions::loadLanguage('plg_editors-xtd_tooltips');

		RLFunctions::script('regularlabs/script.min.js', '16.4.13421');
		RLFunctions::stylesheet('regularlabs/style.min.css', '16.4.13421');

		$this->params->tag = preg_replace('#[^a-z0-9-_]#s', '', $this->params->tag);

		$text = $this->getExampleText();
		$text = str_replace('\\\\n', '\\n', addslashes($text));
		$text = str_replace('{', '{\'+\'', $text);

		$js = "
			function insertTooltips(editor) {
				selection = RegularLabsScripts.getEditorSelection(editor);
				selection = selection ? selection : '" . JText::_('TT_LINK', true) . "';

				text = '" . $text . "';
				text = text.replace('[:SELECTION:]', selection);

				jInsertEditorText(text, editor);
			}
		";
		JFactory::getDocument()->addScriptDeclaration($js);

		$button = new JObject;

		$icon = 'reglab icon-tooltips';

		$button->modal   = false;
		$button->class   = 'btn';
		$button->link    = '#';
		$button->onclick = 'insertTooltips(\'' . $name . '\');return false;';
		$button->text    = $this->getButtonText();
		$button->name    = $icon;

		return $button;
	}

	private function getButtonText()
	{
		$text_ini = strtoupper(str_replace(' ', '_', $this->params->button_text));
		$text     = JText::_($text_ini);
		if ($text == $text_ini)
		{
			$text = JText::_($this->params->button_text);
		}

		return trim($text);
	}

	private function getExampleText()
	{
		switch (true)
		{
			case ($this->params->button_use_custom_code && $this->params->button_custom_code):
				return $this->getCustomText();
			default:
				return $this->getDefaultText();
		}
	}

	private function getDefaultText()
	{
		return '{' . $this->params->tag . ' ' . JText::_('TT_TITLE') . '::' . JText::_('TT_TEXT') . '}[:SELECTION:]{/' . $this->params->tag . '}';
	}

	private function getCustomText()
	{
		$text = trim($this->params->button_custom_code);
		$text = str_replace(array("\r", "\n"), array('', '</p>\n<p>'), trim($text)) . '</p>';
		$text = preg_replace('#^(.*?)</p>#', '\1', $text);
		$text = str_replace(array('{tip', '{/tip}'), array('{' . $this->params->tag, '{/' . $this->params->tag . '}'), trim($text));

		return $text;
	}
}

<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2013
 * @author     Yanick Witschi <yanick.witschi@terminal42.ch>
 * @license    LGPL
 */

/**
 * DSI
 */
$GLOBALS['TL_LANG']['XPL']['dsi_expl'] = array
(
	array('colspan', 'For the settings of the DSI there are several things you should pay attention to:'),
	array('Number of pages per call', 'With this setting you can tell the DSI how many pages it shall process per call. Note: No matter what you are changing here, the DSI will <strong>always</strong> work on the next stack once every hour.'),
	array('Rebuild frequency', 'This setting has in <strong>no way any relation</strong> to the "Number of pages per call" setting! It simply defines, how often DSI is going to rebuild <strong>the whole</strong> search index. E.g. if you set this setting to "daily" and the DSI is still working on the previous stack (because e.g. there are 5000 pages and you told the DSI to only take 100 pages per call [5000/100 = 50 calls = ~50h]), it will finish the previous stack and then continue with the next one.')
);
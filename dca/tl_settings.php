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
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(';{backend_legend}', ';{dsi_legend},dsi_activate;{backend_legend}', $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'dsi_activate';


/**
 * Extend subpalette
 */
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['dsi_activate'] = 'dsi_ppc,dsi_cf';


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['dsi_activate'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_settings']['dsi_activate'],
	'inputType'		=> 'checkbox',
	'eval'      	=> array('tl_class'=>'clr', 'submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['dsi_ppc'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_settings']['dsi_ppc'],
	'inputType'		=> 'text',
	'explanation'	=> 'dsi_expl',
	'eval'			=> array('rgxp'=>'digit', 'tl_class'=>'w50', 'helpwizard'=>true)
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['dsi_cf'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_settings']['dsi_cf'],
	'inputType'		=> 'select',
	'options'		=> array('daily', 'weekly', '30_days'),
	'reference'		=> &$GLOBALS['TL_LANG']['tl_settings']['dsi_cf'],
	'explanation'	=> 'dsi_expl',
	'eval'     		=> array('tl_class'=>'w50', 'helpwizard'=>true)
);
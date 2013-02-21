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
	array('colspan', 'Bei den Einstellungen zum DSI gibt ein paar Dinge zu beachten:'),
	array('Anzahl Seiten pro Aufruf', 'Mit dieser Einstellung können Sie dem DSI sagen, wie viele Seiten er pro Aufruf abarbeiten soll. Beachten Sie: Egal was Sie einstellen, der DSI wird <strong>immer</strong> stündlich den nächsten Satz von Seiten abarbeiten.'),
	array('Neuaufbau-Frequenz', 'Diese Einstellung steht <strong>nicht</strong> in Relation zu "Anzahl Seiten pro Aufruf"! Sie bestimmt lediglich, wie häufig der DSI Ihren <strong>ganzen</strong> Suchindex neu indexieren soll. Stellen Sie hier z.B. "täglich" ein und der DSI ist noch nicht fertig mit dem Abarbeiten des vorherigen Stacks (weil z.B. 5000 Seiten vorhanden sind und Sie eingestellt haben, er soll immer nur 100 pro Aufruf nehmen [5000/100 = 50 Aufrufe = ~50h]), so wird er einfach den vorherigen Job zu Ende bringen und dann den nächsten anfangen.')
);
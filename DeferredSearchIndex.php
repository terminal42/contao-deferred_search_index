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
 * @copyright    terminal42 gmbh 2014
 * @author       Yanick Witschi <yanick.witschi@terminal42.ch>
 * @license      LGPL
 */


/**
 * Class DeferredSearchIndex
 */
class DeferredSearchIndex extends Backend
{

    /**
     * Current object instance (Singleton)
     * @var object
     */
    protected static $objInstance;

    /**
     * Prevent direct instantiation (Singleton)
     */
    protected function __construct() {}


    /**
     * Prevent cloning of the object (Singleton)
     */
    final private function __clone() {}

    /**
     * Index the pages
     */
    public function run()
    {
        // abort if the X-Contao-DSI HTTP header is set because then this page gets called from the DSI itself and this prevents looping
        if (!$GLOBALS['TL_CONFIG']['enableSearch'] || $_SERVER['HTTP_X_CONTAO_DSI']) {
            return;
        }

        // try to get the last item we have indexed
        $objLastIndex = Database::getInstance()->query("SELECT id FROM tl_dsi WHERE lastIndex=1");

        if (!$objLastIndex->numRows && $this->needsUpdate()) {
            $this->collectData();
            $this->continueIndexing(1, $GLOBALS['TL_CONFIG']['dsi_ppc']);
        } else {
            $this->continueIndexing($objLastIndex->id + 1, $GLOBALS['TL_CONFIG']['dsi_ppc']);
        }
    }


    /**
     * Collects all the data and stores it into the database
     */
    private function collectData()
    {
        // we collect new data so let's first truncate the table
        $this->import('Database');
        Database::getInstance()->query('TRUNCATE TABLE tl_dsi');

        // Delete all search indexes for pages no longer present in tl_page
        Database::getInstance()->query("DELETE FROM tl_search WHERE pid NOT IN (SELECT id FROM tl_page)");
        Database::getInstance()->query("DELETE FROM tl_search_index WHERE pid NOT IN (SELECT id FROM tl_search)");

        // get the searchable pages
        $arrPages = $this->findSearchablePages();

        // HOOK: take additional pages
        if (isset($GLOBALS['TL_HOOKS']['getSearchablePages']) && is_array($GLOBALS['TL_HOOKS']['getSearchablePages'])) {
            foreach ($GLOBALS['TL_HOOKS']['getSearchablePages'] as $callback) {
                $this->import($callback[0]);
                $arrPages = $this->$callback[0]->$callback[1]($arrPages);
            }
        }

        // HOOK: add pages that are only for the DSI
        if (isset($GLOBALS['TL_HOOKS']['dsi_searchablePages']) && is_array($GLOBALS['TL_HOOKS']['dsi_searchablePages'])) {
            foreach ($GLOBALS['TL_HOOKS']['dsi_searchablePages'] as $callback) {
                $this->import($callback[0]);
                $arrPages = $this->$callback[0]->$callback[1]($arrPages);
            }
        }

        // prepare the bulk insert
        $strValues = '';

        foreach ($arrPages as $k => $strPageUrl) {
            $strValues .= '(\'' . $strPageUrl . '\'),';

            // save memory
            unset($arrPages[$k]);
        }

        // remove the last "," - I guess this is faster than counting the elements and only add the comma if it's not the last element
        $strValues = substr($strValues, 0, -1);

        // insert the data into the database
        Database::getInstance()->query('INSERT DELAYED INTO tl_dsi (url) VALUES ' . $strValues);
    }


    /**
     * Index the pages
     * @param integer the start id
     * @param integer number of items
     * @param boolean remove the existing last index
     */
    private function continueIndexing($intStartId, $intNumberOfItems)
    {

        // calculate the finish id
        $intFinishId = $intStartId + $intNumberOfItems - 1;

        // get the page urls
        $this->import('Database');
        $objTotal = Database::getInstance()->query('SELECT COUNT(id) AS total FROM tl_dsi');
        $objPages = Database::getInstance()->prepare('SELECT id,url FROM tl_dsi WHERE id>=? AND id<=?')->execute($intStartId, $intFinishId);

        $intLastId = 0;

        while ($objPages->next()) {
            // request the url
            $objRequest = new Request();
            $objRequest->setHeader('X-Contao-DSI', '1');
            $objRequest->send($objPages->url);

            $intLastId = $objPages->id;
        }

        // log
        $this->log('DSI: Indexed URIs ' . $intStartId . ' to ' . $intLastId . ' of ' . $objTotal->total, __METHOD__, TL_CRON);

        // remove the current iterator
        Database::getInstance()->query("UPDATE tl_dsi SET lastindex='' WHERE lastIndex=1");


        // now let's check if there are still urls to work on
        $objMore = Database::getInstance()->prepare('SELECT COUNT(id) AS hasMore FROM tl_dsi WHERE id<?')->execute($intLastId);

        if ($objMore->hasMore) {
            // update the last item
            Database::getInstance()->prepare('UPDATE tl_dsi SET lastIndex=? WHERE id=?')->execute(1, $intLastId);
        }
    }


    /**
     * Compares the current time to the last time DSI ran
     * @return boolean
     */
    private function needsUpdate()
    {
        // if theres no dsi_lastStart set we need the update
        if (!$GLOBALS['TL_CONFIG']['dsi_lastStart']) {
            return true;
        }

        // otherwise we compare
        $intRange = 0;

        switch ($GLOBALS['TL_CONFIG']['dsi_cf']) {
            case 'daily':
                $intRange = 86400;
                break;
            case 'weekly':
                $intRange = 604800;
                break;
            case '30_days':
                $intRange = 2592000;
                break;
        }

        // compare the last run with the current time
        $intDifference = time() - $GLOBALS['TL_CONFIG']['dsi_lastStart'];

        if ($intDifference >= $intRange) {
            $this->Config->update("\$GLOBALS['TL_CONFIG']['dsi_lastStart']", time());
            return true;
        }

        return false;
    }


    /**
     * Return the current object instance (Singleton)
     * @return object
     */
    public static function getInstance()
    {
        if (!is_object(self::$objInstance)) {
            self::$objInstance = new DeferredSearchIndex();
        }

        return self::$objInstance;
    }
}
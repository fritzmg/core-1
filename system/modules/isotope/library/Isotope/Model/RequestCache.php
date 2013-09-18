<?php

/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2012 Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://www.isotopeecommerce.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 */

namespace Isotope\Model;

use Isotope\RequestCache\Filter;
use Isotope\RequestCache\Limit;
use Isotope\RequestCache\Sort;

/**
 * Isotope\Model\RequestCache represents an Isotope request cache model
 *
 * @copyright  Isotope eCommerce Workgroup 2009-2012
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 */
class RequestCache extends \Model
{

    /**
     * Name of the current table
     * @var string
     */
    protected static $strTable = 'tl_iso_requestcache';

    /**
     * Modified flag
     * @var bool
     */
    protected $blnModified = false;

    /**
     * Filter configuration
     * @var array
     */
    protected $arrFilters;

    /**
     * Sorting configuration
     * @var array
     */
    protected $arrSortings;

    /**
     * Limit configuration
     * @var array
     */
    protected $arrLimits;


    /**
     * Check if request cache is empty
     * @return  bool
     */
    public function isEmpty()
    {
        return (null === $this->getFilters() && null === $this->getSortings() && null === $this->getLimits());
    }

    /**
     * Check if request chace is modified
     * @return  bool
     */
    public function isModified()
    {
        return $this->blnModified;
    }

    /**
     * Get filter configuration
     * @return  array|null
     */
    public function getFilters()
    {
        return $this->arrFilters;
    }

    /**
     * Get filter config for multiple modules
     * @param   array
     * @return  array
     */
    public function getFiltersForModules(array $arrIds)
    {
        if ($this->arrFilters === null) {
            return array();
        }

        return call_user_func_array('array_merge', array_intersect_key($this->arrFilters, array_flip(array_reverse($arrIds))));
    }

    /**
     * Set filter config for a frontend module
     * @param   array
     * @param   int
     */
    public function setFiltersForModule(array $arrFilters, $intModule)
    {
        $this->blnModified = true;

        $this->arrFilters[$intModule] = $arrFilters;
    }

    /**
     * Remove all filters for a frontend module
     */
    public function unsetFiltersForModule($intModule)
    {
        if (isset($this->arrFilters[$intModule])) {
            unset($this->arrFilters[$intModule]);
            $this->blnModified = true;
        }
    }

    /**
     * Return a specific filter by name and module
     * @param   string
     * @param   int
     * @return  Filter|null
     */
    public function getFilterForModule($strName, $intModule)
    {
        if (!isset($this->arrFilters[$intModule]) || !isset($this->arrFilters[$intModule][$strName])) {
            return null;
        }

        return $this->arrFilters[$intModule][$strName];
    }

    /**
     * Add an additional filter for a frontend module
     * @param   Filter
     * @param   int
     */
    public function addFilterForModule(Filter $objFilter, $intModule)
    {
        $this->arrFilters[$intModule][] = $objFilter;
        $this->blnModified = true;
    }

    /**
     * Set filter by name for a frontend module
     * @param   string
     * @param   Filter
     * @param   int
     */
    public function setFilterForModule($strName, Filter $objFilter, $intModule)
    {
        $this->arrFilters[$intModule][$strName] = $objFilter;
        $this->blnModified = true;
    }

    /**
     * Remove a filter for a frontend module
     * @param   string
     * @param   int
     */
    public function removeFilterForModule($strName, $intModule)
    {
        if (isset($this->arrFilters[$intModule]) || isset($this->arrFilters[$intModule][$strName])) {
            $this->blnModified = true;

            unset($this->arrFilters[$intModule][$strName]);

            if (empty($this->arrFilters[$intModule])) {
                unset($this->arrFilters[$intModule]);
            }
        }
    }

    /**
     * Get sorting configuration
     * @return  array|null
     */
    public function getSortings()
    {
        return $this->arrSortings;
    }

    /**
     * Get sorting configs for multiple modules
     * @param   array
     * @return  array
     */
    public function getSortingsForModules(array $arrIds)
    {
        if (null === $this->arrSortings) {
            return array();
        }

        return call_user_func_array('array_merge', array_intersect_key($this->arrSortings, array_flip(array_reverse($arrIds))));
    }

    /**
     * Set sorting config for a frontend module
     * @param   array
     * @param   int
     */
    public function setSortingsForModule(array $arrSortings, $intModule)
    {
        $this->arrSortings[$intModule] = $arrSortings;
        $this->blnModified = true;
    }

    /**
     * Remove sorting configs for a frontend module
     * @param   int
     */
    public function unsetSortingsForModule($intModule)
    {
        if (isset($this->arrSortings[$intModule])) {
            unset($this->arrSortings[$intModule]);
            $this->blnModified = true;
        }
    }

    /**
     * Get first sorting field name for a frontend module
     * @param   int
     * @return  string
     */
    public function getFirstSortingFieldForModule($intModule)
    {
        if (null === $this->arrSortings || !is_array($this->arrSortings[$intModule])) {
            return '';
        }

        $arrNames = array_keys($this->arrSortings[$intModule]);

        return reset($arrNames);
    }

    /**
     * Return a specific sorting by name and module
     * @param   string
     * @param   int
     * @return  Sort|null
     */
    public function getSortingForModule($strName, $intModule)
    {
        if (!isset($this->arrSortings[$intModule]) || !isset($this->arrSortings[$intModule][$strName])) {
            return null;
        }

        return $this->arrSortings[$intModule][$strName];
    }

    /**
     * Add an additional sorting for a frontend module
     * @param   Sort
     * @param   int
     */
    public function addSortingForModule(Sort $objSort, $intModule)
    {
        if (null === $this->arrSortings || !is_array($this->arrSortings[$intModule])) {
            $this->arrSortings[$intModule] = array();
        }

        $this->arrSortings[$intModule] = array_merge(array($objSort), $this->arrSortings[$intModule]);
        $this->blnModified = true;
    }

    /**
     * Set sorting by name for a frontend module
     * @param   string
     * @param   Sort
     * @param   int
     */
    public function setSortingForModule($strName, Sort $objSort, $intModule)
    {
        if (null === $this->arrSortings || !is_array($this->arrSortings[$intModule])) {
            $this->arrSortings[$intModule] = array();
        }

        if (isset($this->arrSortings[$intModule][$strName])) {
            unset($this->arrSortings[$intModule][$strName]);
        }

        $this->arrSortings[$intModule] = array_merge(array($strName=>$objSort), $this->arrSortings[$intModule]);
        $this->blnModified = true;
    }

    /**
     * Remove a sorting for a frontend module
     * @param   string
     * @param   int
     */
    public function removeSortingForModule($strName, $intModule)
    {
        if (isset($this->arrSortings[$intModule]) || isset($this->arrSortings[$intModule][$strName])) {
            $this->blnModified = true;

            unset($this->arrSortings[$intModule][$strName]);

            if (empty($this->arrSortings[$intModule])) {
                unset($this->arrSortings[$intModule]);
            }
        }
    }

    /**
     * Get limit configuration
     * @return  array|null
     */
    public function getLimits()
    {
        return $this->arrLimits;
    }

    /**
     * Set limit for a frontend module
     * @param   Limit
     * @param   int
     */
    public function setLimitForModule(Limit $objLimit, $intModule)
    {
        $this->arrLimits[$intModule] = $objLimit;
        $this->blnModified = true;
    }

    /**
     * Return the first limit we can find
     * @param   array
     * @param   int
     * @return  int
     */
    public function getFirstLimitForModules(array $arrIds, $intDefault=0)
    {
        if (null !== $this->arrLimits) {
            foreach ($arrIds as $id) {
                if (isset($this->arrLimits[$id])) {
                    return $this->arrLimits[$id];
                }
            }
        }

        return Limit::to($intDefault);
    }

    /**
     * Do not allow to overwrite existing cache
     * @param   bool
     * @return  RequestCache
     * @throws  \BadMethodCallException
     */
    public function save($blnForceInsert=false)
    {
        if ($this->blnModified && $this->id > 0 && !$blnForceInsert) {
            throw new \BadMethodCallException('Can\'t save a modified cache');
        }

        return parent::save($blnForceInsert);
    }

    /**
     * Return cache matching the current config, create or update if necessary
     * @return  RequestCache
     */
    public function saveNewConfiguartion()
    {
        if (!$this->blnModified) {
            return $this;
        }

        $objCache = static::findOneBy(array('store_id=?', 'config=?'), $this->preSave(array($this->store_id)));

        if (null === $objCache) {
            $objCache = clone $this;
        } elseif ($objCache->id == $this->id) {
            return $this;
        }

        $objCache->tstamp = time();

        return $objCache->save();
    }

	/**
	 * Set the current record from an array
	 * @param   array
	 * @return  \Model
	 */
    public function setRow(array $arrData)
    {
        $arrConfig = deserialize($arrData['config']);

        $this->arrFilters = $arrConfig['filters'];
        $this->arrSortings = $arrConfig['sortings'];
        $this->arrLimits = $arrConfig['limits'];

        return parent::setRow($arrData);
    }

    /**
     * Add object data to row
     * @param   array
     * @return  array
     */
    protected function preSave(array $arrSet)
    {
        $arrSet['config'] = array(
            'filters'   => (empty($this->arrFilters) ? null : $this->arrFilters),
            'sortings'  => (empty($this->arrSortings) ? null : $this->arrSortings),
            'limits'    => (empty($this->arrLimits) ? null : $this->arrLimits)
        );

        return $arrSet;
    }

    /**
     * Find cache by ID and store
     * @param   int
     * @param   int
     * @return  RequestCache|null
     */
    public static function findByIdAndStore($intId, $intStore, array $arrOptions=array())
    {
        return static::findOneBy(array('id=?', 'store_id=?'), array($intId, $intStore), $arrOptions);
    }

    /**
     * Delete a cache by ID
     * @param   int
     */
    public static function deleteById($intId)
    {
        return (\Database::getInstance()->prepare("DELETE FROM " . static::$strTable . " WHERE id=?")->execute($intId)->affectedRows > 0);
    }

    /**
     * Purge the request cache
     */
    public static function purge()
    {
        \Database::getInstance()->query("TRUNCATE " . static::$strTable);
    }
}
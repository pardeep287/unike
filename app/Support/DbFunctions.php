<?php

use App\FinancialYear;
use App\TaxRates;
use App\Menu;

/**
 * :: Db Functions File ::
 * USed for manage all kind database related helper functions.
 *
 **/

/**
 * @return null
 */
function loggedInCompanyId()
{

    return authUser()->company_id;
}

/**
 * @return null
 */
function financialYearId()
{
	$result = (new FinancialYear)->getActiveFinancialYear();
	if ($result) {
		return $result->id;
	}
	return null;
}

/**
 * Method is used on each and every view to display current financial year
 * @return null
 */
function getActiveFinancialYear()
{
    $result = (new FinancialYear)->getActiveFinancialYear();
    if ($result) {
        return $result->name;
    }
    return null;
}

/**
 * @param $id
 * @param $date
 * @return mixed
 */
function getEffectedTaxRate($id, $date)
{
	$result = (new TaxRates)->getEffectedTaxRate($id, $date);
	return $result;
}

/**
 * @return array
 * @Author Inderjit Singh
 */
function renderMenus() {
   $menus = (new Menu)->getMenuNavigation(true, true);
   return $menus;
}

/*
 * @return Array
 */
function getQuickMenu() {
    $tree = (new Menu)->getMenuNavigation(true, false);
    if(count($tree) > 0) {
        $quickMenuArr = [];
        foreach ($tree as $firstLevel) {
            if(array_key_exists('child', $firstLevel)) {

                foreach($firstLevel['child'] as $key => $value) {
                    if(array_key_exists('quick_menu', $value)) {
                        array_push($quickMenuArr, $value);
                    }
                    else
                        continue;
                }
            }
        }
    }
    return $quickMenuArr;
}

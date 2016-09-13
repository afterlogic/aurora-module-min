<?php

/* -AFTERLOGIC LICENSE HEADER- */

/**
 * @package Min
 * @subpackage Storages
 */
class CApiMinNodbStorage extends CApiMinMainStorage
{
	/**
	 * @param CApiGlobalManager &$oManager
	 */
	public function __construct(AApiManager &$oManager)
	{
		parent::__construct('nodb', $oManager);
	}
}

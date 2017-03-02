<?php
/**
 * @copyright Copyright (c) 2017, Afterlogic Corp.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 * 
 * @package Modules
 */

/**
 * @package Min
 * @subpackage Storages
 */
class CApiMinStorage extends \Aurora\System\AbstractManagerStorage
{
	/**
	 * @param \Aurora\System\GlobalManager &$oManager
	 */
	public function __construct($sStorageName, \Aurora\System\AbstractManager &$oManager)
	{
		parent::__construct('min', $sStorageName, $oManager);
	}
}
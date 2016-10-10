<?php
/**
 * @copyright Copyright (c) 2016, Afterlogic Corp.
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 * 
 * @package Modules
 */

class MinModule extends AApiModule
{
	public $oApiMinManager = null;
	
	/***** private functions *****/
	/**
	 * Initializes module.
	 * 
	 * @ignore
	 */
	public function init()
	{
		parent::init();
		
		$this->oApiMinManager = $this->GetManager();
		$this->AddEntry('window', 'EntryMin');
		$this->subscribeEvent('Core::CreateTables::after', array($this, 'onAfterCreateTables'));
	}
	
	/**
	 * Creates tables required for module work. Called by event subscribe.
	 * 
	 * @ignore
	 * @param array $aParams Parameters
	 */
	public function onAfterCreateTables($aParams, &$mResult)
	{
		$mResult = $this->oApiMinManager->createTablesFromFile();
	}
	/***** private functions *****/
	
	/***** public functions *****/
	/**
	 * @ignore
	 * @return string
	 */
	public function EntryMin()
	{
		$sResult = '';
		$aPaths = \System\Service::GetPaths();
		$sModule = empty($aPaths[1]) ? '' : $aPaths[1];
		try
		{
			if (!empty($sModule))
			{
//				\CApi::GetModuleManager()->ExecuteMethod($sModule, $sMethod, $aParameters);
				if (/*method_exists($this->oActions, $sMethodName)*/ true)
				{
					if ('Min' === $aPaths[1])
					{
						$mHashResult = $this->oApiMinManager->getMinByHash(empty($aPaths[2]) ? '' : $aPaths[2]);
						
						$this->oActions->SetActionParams(array(
							'Result' => $mHashResult,
							'Hash' => empty($aPaths[2]) ? '' : $aPaths[2],
						));
					}
					else
					{
						$this->oActions->SetActionParams(array(
							'AccountID' => empty($aPaths[2]) || '0' === (string) $aPaths[2] ? '' : $aPaths[2],
							'RawKey' => empty($aPaths[3]) ? '' : $aPaths[3]
						));
					}
					
					$mResult = call_user_func(array($this->oActions, $sMethodName));
					$sTemplate = isset($mResult['Template']) && !empty($mResult['Template']) &&
						is_string($mResult['Template']) ? $mResult['Template'] : null;
					
					if (!empty($sTemplate) && is_array($mResult) && file_exists(AURORA_APP_ROOT_PATH.$sTemplate))
					{
						$sResult = file_get_contents(AURORA_APP_ROOT_PATH.$sTemplate);
						if (is_string($sResult))
						{
							$sResult = strtr($sResult, $mResult);
						}
						else
						{
							\CApi::Log('Empty template.', \ELogLevel::Error);
						}
					}
					else if (!empty($sTemplate))
					{
						\CApi::Log('Empty template.', \ELogLevel::Error);
					}
					else if (true === $mResult)
					{
						$sResult = '';
					}
					else
					{
						\CApi::Log('False result.', \ELogLevel::Error);
					}
				}
				else
				{
					\CApi::Log('Invalid action.', \ELogLevel::Error);
				}
			}
			else
			{
				\CApi::Log('Empty action.', \ELogLevel::Error);
			}
		}
		catch (\Exception $oException)
		{
			\CApi::LogException($oException);
		}
		
		return $sResult;
	}
	/***** public functions *****/
	
	/***** public functions might be called with web API *****/
	/**
	 * Crates min hash.
	 * 
	 * @param string $HashId Hash identificator.
	 * @param array $Parameters Hash parameters.
	 * @return string|boolean
	 */
	public function CreateMin($HashId, $Parameters)
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::NormalUser);
		
		return $this->oApiMinManager->createMin($HashId, $Parameters);
	}
	
	/**
	 * Returns parameters object by min hash.
	 * 
	 * @param string $sHash Min hash.
	 * @return array|bool
	 */
	public function GetMinByHash($sHash)
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
		return $this->oApiMinManager->getMinByHash($sHash);
	}
	
	/**
	 * Returns parameters object by min hash identificator.
	 * 
	 * @param string $Id
	 * @return array|bool
	 */
	public function GetMinByID($Id)
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
		return $this->oApiMinManager->getMinByID($Id);
	}
	
	/**
	 * Updates min hash by min hash identificator.
	 * 
	 * @param string $Id Hash identificator.
	 * @param array $Data Hash parameters.
	 * @param string $NewId New hash identificator.
	 * @return boolean
	 */
	public function UpdateMinByID($Id, $Data, $NewId = null)
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::NormalUser);
		
		return $this->oApiMinManager->updateMinByID($Id, $Data, $NewId);
	}
	
	/**
	 * Updates min hash by min hash.
	 * 
	 * @param string $Hash Min hash.
	 * @param array $Data Hash parameters.
	 * @param string $NewHash New min hash.
	 * @return boolean
	 */
	public function UpdateMinByHash($Hash, $Data, $NewHash = null)
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::Anonymous);
		
		return $this->oApiMinManager->updateMinByHash($Hash, $Data, $NewHash);
	}
	
	/**
	 * Deletes min hash by min hash identificator.
	 * 
	 * @param string $Id
	 * @return boolean
	 */
	public function DeleteMinByID($Id)
	{
		\CApi::checkUserRoleIsAtLeast(\EUserRole::NormalUser);
		
		return $this->oApiMinManager->deleteMinByID($Id);
	}
	/***** public functions might be called with web API *****/
}

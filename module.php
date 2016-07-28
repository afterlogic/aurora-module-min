<?php

class MinModule extends AApiModule
{
	public $oApiMinManager = null;

	public function init() {
		parent::init();
		
		$this->oApiMinManager = $this->GetManager();
		$this->AddEntry('window', 'EntryMin');
		$this->subscribeEvent('Core::CreateTables::after', array($this, 'onAfterCreateTables'));
	}
	
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
					if ('Min' === $aPaths[0])
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

					if (!empty($sTemplate) && is_array($mResult) && file_exists(PSEVEN_APP_ROOT_PATH.$sTemplate))
					{
						$sResult = file_get_contents(PSEVEN_APP_ROOT_PATH.$sTemplate);
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
	
	public function CreateMin($HashId, $Parameters)
	{
		return $this->oApiMinManager->createMin($HashId, $Parameters);
	}

	public function GetMinByHash($sHash)
	{
		return $this->oApiMinManager->getMinByHash($sHash);
	}
	
	public function GetMinByID($Id)
	{
		return $this->oApiMinManager->getMinByID($Id);
	}

	public function UpdateMinByID($Id, $Data, $NewId)
	{
		return $this->oApiMinManager->updateMinByID($Id, $Data, $NewId);
	}
	
	public function DeleteMinByID($Id)
	{
		return $this->oApiMinManager->deleteMinByID($Id);
	}
	
	/**
	 * Creates tables required for module work. Called by event subscribe.
	 * 
	 * @param array $aParams Parameters
	 */
	public function onAfterCreateTables($aParams)
	{
		$aParams['@Result'] = $this->oApiMinManager->createTablesFromFile();
	}
}

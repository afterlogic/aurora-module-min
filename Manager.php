<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\Min;

use Aurora\Modules\Min\Models\MinHash;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing Afterlogic Software License
 * @copyright Copyright (c) 2019, Afterlogic Corp.
 */
class Manager extends \Aurora\System\Managers\AbstractManager
{
	public function __construct(\Aurora\System\Module\AbstractModule $oModule = null)
	{
		parent::__construct($oModule);
	}

	/**
	 * @param string $sHashID
	 * @param array $aParams
	 *
	 * @return string|bool
	 */
	public function createMin($sHashID, $aParams, $iUserId = null, $iExpireDate = null)
	{
		$mResult = false;
		$sNewMin = '';

		if (is_string($sHashID) && 0 < strlen($sHashID) && false !== $this->getMinByID($sHashID))
		{
			return false;
		}

		while (true)
		{
			$sNewMin = \Aurora\System\Utils::GenerateShortHashString(10);
			if (false === $this->getMinByHash($sNewMin))
			{
				break;
			}
		}

		if (0 < strlen($sNewMin))
		{
			$aParams['__hash_id__'] = $sHashID;
			$aParams['__hash__'] = $sNewMin;
			$aParams['__time__'] = time();
			$aParams['__time_update__'] = time();

			if (MinHash::create([
				'hash' => $sNewMin,
				'hash_id' => md5($sHashID),
				'user_id' => $iUserId,
				'data' =>  @\json_encode($aParams),
				'expire_date' => $iExpireDate
			])) {
				$mResult = $sNewMin;
			}
		}

		return $mResult;
	}

		/**
	 * @return array|bool
	 */
	private function parseGetMinDbResult($oMin)
	{
		$mResult = false;

		if ($oMin && !empty($oMin->data))
		{
			$aData = @\json_decode($oMin->data, true);
			if (is_array($aData) && 0 < count($aData))
			{
				$mResult = $aData;
			}
			if ($oMin->expire_date)
			{
				$mResult['expire_date'] = $oMin->expire_date;
			}
		}

		return $mResult;
	}

	/**
	 * @param string $sHashID
	 *
	 * @return array|bool
	 */
	public function getMinByID($sHashID)
	{
		$oMin = MinHash::firstWhere('hash_id', \md5($sHashID));
		return $this->parseGetMinDbResult($oMin);
	}

	/**
	 * @param string $sHash
	 *
	 * @return array|bool
	 */
	public function getMinByHash($sHash)
	{
		$oMin = MinHash::firstWhere('hash', $sHash);
		return $this->parseGetMinDbResult($oMin);
	}

	/**
	 * @param int $iUserId
	 *
	 * @return array|bool
	 */
	public function getMinListByUserId($iUserId)
	{
		return MinHash::where('user_id', $iUserId)->get();
	}

	/**
	 * @param string $sHashID
	 *
	 * @return bool
	 */
	public function deleteMinByID($sHashID)
	{
		return !!MinHash::where('hash_id', \md5($sHashID))->delete();
	}

	/**
	 * @param string $sHash
	 *
	 * @return bool
	 */
	public function deleteMinByHash($sHash)
	{
		return !!MinHash::where('hash', $sHash)->delete();
	}

	/**
	 * @param string $sHashID
	 * @param array $aParams
	 * @param string $sNewHashID Default value is **null**
	 *
	 * @return bool
	 */
	public function updateMinByID($sHashID, $aParams, $sNewHashID = null)
	{
		$aPrevParams = $this->getMinByID($sHashID);
		if (isset($aPrevParams['__hash__']))
		{
			$aParams['__hash__'] = $aPrevParams['__hash__'];
		}
		if (!empty($sNewHashID))
		{
			$aParams['__hash_id__'] = $sNewHashID;
		}
		if (isset($aPrevParams['__time__']))
		{
			$aParams['__time__'] = $aPrevParams['__time__'];
		}

		$aParams['__time_update__'] = time();
		$aMergedParams = array_merge($aPrevParams, $aParams);

		$aUpdate = [
			'data' => @\json_encode($aMergedParams)
		];
		if (!empty($sNewHashID)) {
			$aUpdate['hash_id'] = \md5($sNewHashID);
		}

		return MinHash::where('hash_id', \md5($sHashID))->udate($aUpdate);
	}

	/**
	 * @param string $sHash
	 * @param array $aParams
	 * @param string $sNewHashID Default value is **null**
	 *
	 * @return bool
	 */
	public function updateMinByHash($sHash, $aParams, $sNewHashID = null)
	{
		$aPrevParams = $this->getMinByHash($sHash);
		if (isset($aPrevParams['__hash_id__']))
		{
			$aParams['__hash_id__'] = $aPrevParams['__hash_id__'];
		}
		if (!empty($sNewHashID))
		{
			$aParams['__hash_id__'] = $sNewHashID;
		}
		if (isset($aPrevParams['__time__']))
		{
			$aParams['__time__'] = $aPrevParams['__time__'];
		}

		$aParams['__time_update__'] = time();

		$aUpdate = [
			'data' => @\json_encode($aParams)
		];
		if (!empty($sNewHashID)) {
			$aUpdate['hash_id'] = \md5($sNewHashID);
		}

		return MinHash::where('hash', $sHash)->udate($aUpdate);
	}
}

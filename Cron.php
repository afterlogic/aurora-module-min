<?php

namespace Aurora\Modules\Min;

require_once dirname(__file__) . '/../../system/autoload.php';

\Aurora\System\Api::Init(true);

class SelfDestructingMinHashes
{
    public static function NewInstance()
    {
        return new self();
    }

    public function Execute()
    {
        \Aurora\System\Api::Log('---------- Start SelfDestructingMinHashes cron script', \Aurora\System\Enums\LogLevel::Full, 'cron-');

        try {
            Models\MinHash::whereNotNull('ExpireDate')->where('ExpireDate', '<=', \time())->delete();
        } catch(\Exception $e) {
            \Aurora\System\Api::Log('Error during SelfDestructingMinHashes cron script execution. ', \Aurora\System\Enums\LogLevel::Full, 'cron-');
            \Aurora\System\Api::LogException($e, \Aurora\System\Enums\LogLevel::Full, 'cron-');
        }

        \Aurora\System\Api::Log('---------- End SelfDestructingMinHashes cron script', \Aurora\System\Enums\LogLevel::Full, 'cron-');
    }
}

$iTimer = microtime(true);

SelfDestructingMinHashes::NewInstance()->Execute();

\Aurora\System\Api::Log('Cron SelfDestructingMinHashes execution time: '.(microtime(true) - $iTimer).' sec.', \Aurora\System\Enums\LogLevel::Full, 'cron-');

<?php declare(strict_types=1);

namespace App;

use Swoft\Log\Helper\CLog;
use Swoft\SwoftApplication;
use function date_default_timezone_set;

/**
 * Class Application
 *
 * @since 2.0
 */
class Application extends SwoftApplication
{

/*    /**
     * @Inject()
     *
     * @var Config
     */
    /*private $config;*/

    protected function beforeInit(): void
    {
        parent::beforeInit();

        date_default_timezone_set('Asia/Shanghai');
    }

    public function beforeRun(): bool
    {
/*        $nameSpaces = ['application','TEST1.shop','merchant'];
        $retData = $this->config->batchPull($nameSpaces);
        CLog::debug('  retData:%s',json_encode($retData));
        return $retData;*/

        return true;

    }

    public function getCLoggerConfig(): array
    {
        return [
            'name'    => 'testHttpSwoft',
            'enable'  => true,
            'output'  => true,
            'levels'  => 'info,error',
            'logFile' => ''
        ];
    }
}

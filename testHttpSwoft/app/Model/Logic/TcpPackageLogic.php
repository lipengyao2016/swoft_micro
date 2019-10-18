<?php declare(strict_types=1);


namespace App\Model\Logic;

use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\Log;
use Swoft\Tcp\Package;
use Swoft\Tcp\Packer\SimpleTokenPacker;

/**
 * Class TcpPackageLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class TcpPackageLogic
{
    /**
     * @var SimpleTokenPacker
     */
    private $simpleTokenPacker;

    /**
     * TcpPackageLogic constructor.
     * @param SimpleTokenPacker $simpleTokenPacker
     */
    public function __construct()
    {
        $this->simpleTokenPacker = new SimpleTokenPacker();
    }


    /**
     * @throws ApolloException
     */
    public function encode($cmd,$data)
    {
        Log::debug(__METHOD__.' simpleTokenPacker:'.get_class($this->simpleTokenPacker));
       $package = new Package();
       $package->setCmd($cmd);
       $package->setData($data);
       $packageData = $this->simpleTokenPacker->encode($package);
       return $packageData;
    }
}
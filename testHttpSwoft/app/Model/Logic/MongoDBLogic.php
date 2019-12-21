<?php declare(strict_types=1);


namespace App\Model\Logic;

use App\Common\mongodb\MongoDbDao;
use Swoft\Apollo\Config;
use Swoft\Apollo\Exception\ApolloException;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Swoft\Log\Helper\Log;

/**
 * Class MongoDBLogic
 *
 * @since 2.0
 *
 * @Bean()
 */
class MongoDBLogic
{

    /**
     * ApolloLogic constructor.
     */
    public function __construct()
    {
        $this->configData = null;
    }

    public function getMongoDBDao():MongoDbDao
    {
        $mongodb = new MongoDbDao('47.112.99.100',27891,
            'root','root','sqdj','colleagues');
        return $mongodb;
    }



}
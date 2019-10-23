<?php declare(strict_types=1);


namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class RequestBean
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::REQUEST, name="requestBean")
 */
class RequestBean
{
    protected $data;

    public  function setData($data)
    {
        $this->data = $data;
    }
    /**
     * @return array
     */
    public function getData(): array
    {
        return ['requestBean '.$this->data.' V2.0'];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getName(string $type):string {
        return 'name';
    }
}
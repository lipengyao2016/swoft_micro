<?php declare(strict_types=1);


namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class RequestBean
 *
 * @since 2.0
 *
 * @Bean()
 */
class CommonConfigBean
{
    protected $data;
    protected $curServiceID;

    /**
     * @return mixed
     */
    public function getCurServiceID()
    {
        return $this->curServiceID;
    }

    /**
     * @param mixed $curServiceID
     */
    public function setCurServiceID($curServiceID): void
    {
        $this->curServiceID = $curServiceID;
    }

    public  function setData($data)
    {
        $this->data = $data;
    }
    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


}
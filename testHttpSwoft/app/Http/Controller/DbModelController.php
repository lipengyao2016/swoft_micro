<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\Count;
use App\Model\Entity\User;
use Exception;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Throwable;

/**
 * Class DbModelController
 *
 * @since 2.0
 *
 * @Controller(prefix="dbModel")
 */
class DbModelController
{
    /**
     * @RequestMapping(route="find")
     *
     * @param Response $response
     *
     * @return Response
     *
     * @throws Throwable
     */
    public function find(Response $response): Response
    {
        $id   = $this->getId();
        $user = User::find($id);
        Log::debug(__METHOD__.' after find user:'.json_encode($user));
        return $response->withData($user);
    }

    /**
     * @RequestMapping(route="save")
     *
     * @return array
     *
     * @throws Exception
     */
    public function save(): array
    {
        $user = new User();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');
        $user->save();
        Log::debug(__METHOD__.' user:'.json_encode($user));

        $count = Count::new();
        $count->setUserId($user->getId());
        $count->save();
        Log::debug(__METHOD__.' count:'.json_encode($count));
        return $user->toArray();
    }

    /**
     * @RequestMapping(route="update")
     *
     * @return array
     *
     * @throws Throwable
     */
    public function update(): array
    {
        $id = $this->getId();

        User::updateOrInsert(['id' => $id], ['name' => 'swoft']);

        $user = User::find($id);
        Log::debug(__METHOD__.'  after update  user:'.json_encode($user));
        return $user->toArray();
    }

    /**
     * @RequestMapping(route="delete")
     *
     * @return array
     *
     * @throws Throwable
     */
    public function delete(): array
    {
        $id     = $this->getId();
        Log::debug(__METHOD__.'  begin delete  id:'.$id);
        $result = User::find($id)->delete();
        Log::debug(__METHOD__.'  after delete  result:'.$result);
        return [$result];
    }

    /**
     * @return int
     * @throws Throwable
     */
    public function getId(): int
    {
        $user = new User();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');
        $user->setName('liming');
        $user->setAdd(3);
        $user->setHahh(5);
        $user->setTestJson(['expired' => 20]);

        $user->save();

        Log::debug(__METHOD__.' user:'.json_encode($user));

        return $user->getId();
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function batchUpdate()
    {
        // User::truncate();
        User::updateOrCreate(['id' => 1], ['age' => 23]);
        User::updateOrCreate(['id' => 2], ['age' => 23]);

        $values = [
            ['id' => 1, 'age' => 18],
            ['id' => 2, 'age' => 19],
        ];
        $values = array_column($values, null, 'id');
        $batchUpdateRet = User::batchUpdateByIds($values);
        Log::debug(__METHOD__.' batchUpdateRet:'.$batchUpdateRet);

        $users = User::find(array_column($values, 'id'));

        Log::debug(__METHOD__.' after batch update users:'.json_encode($users));
        $updateResults = [];
        /** @var User $user */
        foreach ($users as $user) {
            $updateResults[$user->getId()] = true;
            if ($user->getAge() != $values[$user->getId()]['age']) {
                $updateResults[$user->getId()] = false;
            }
        }
        Log::debug(__METHOD__.' after batch  updateResults:'.json_encode($updateResults));

        return $updateResults;
    }
}

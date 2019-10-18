<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\Count;
use App\Model\Entity\User;
use App\Model\Entity\User3;
use Swoft\Co;
use Swoft\Db\DB;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Throwable;
use function sgo;

/**
 * Class DbTransactionController
 *
 * @since 2.0
 *
 * @Controller("dbTransaction")
 */
class DbTransactionController
{
    /**
     * @RequestMapping(route="ts")
     *
     * @return false|string
     * @throws Throwable
     */
    public function ts()
    {
        $id = $this->getId();

        DB::beginTransaction();
        $user = User::find($id);
        $user->update(['name' => 'tran2']);

        Log::debug(__METHOD__.' user:'.json_encode($user).
        ' curId:'.Co::id());

        DB::commit();

        sgo(function () use ($id) {
            DB::beginTransaction();
            $user = User::find($id);
            $user->update(['name' => 'tran3']);
            DB::commit();
            Log::debug(__METHOD__.' sgo  curId:'.Co::id());
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="cm")
     *
     * @return false|string
     * @throws Throwable
     */
    public function cm()
    {
        $id = $this->getId();

        DB::beginTransaction();
        $user = User::find($id);
        $user->update(['name' => 'cm3']);
        DB::commit();
        Log::debug(__METHOD__.'   curId:'.Co::id());

        sgo(function () use ($id) {
            DB::beginTransaction();
            $user =  User::find($id);
           $user->update(['name' => 'cm4']);
            DB::commit();
            Log::debug(__METHOD__.'  ago curId:'.Co::id());
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="rl")
     *
     * @return false|string
     * @throws Throwable
     */
    public function rl()
    {
        $id = $this->getId();

        DB::beginTransaction();
        $user = User::find($id);
        $user->update(['name' => 'r1']);
        DB::rollBack();
        Log::debug(__METHOD__.'   curId:'.Co::id());

        sgo(function () use ($id) {
            DB::beginTransaction();
            $user =  User::find($id);
            $user->update(['name' => 'r2']);
            DB::rollBack();
            Log::debug(__METHOD__.'  ago curId:'.Co::id());
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="ts2")
     *
     * @return false|string
     * @throws Throwable
     */
    public function ts2()
    {
        $id = $this->getId();

        DB::connection()->beginTransaction();
        $user = User::find($id);
        $user->update(['name' => 'ts3']);
        Log::debug(__METHOD__.'   curId:'.Co::id());

        sgo(function () use ($id) {
            DB::connection()->beginTransaction();
            $user =  User::find($id);
            $user->update(['name' => 'ts4']);
            Log::debug(__METHOD__.'  ago curId:'.Co::id());
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="cm2")
     *
     * @return false|string
     * @throws Throwable
     */
    public function cm2()
    {
        $id = $this->getId();

        DB::connection()->beginTransaction();
        $user = User::find($id);
        $user->update(['name' => 'cm3']);
        DB::connection()->commit();
        Log::debug(__METHOD__.'   curId:'.Co::id().' user:'.json_encode($user));

        sgo(function () use ($id) {
            DB::connection()->beginTransaction();
            $user =  User::find($id);
            $user->update(['name' => 'cm4']);
            DB::connection()->commit();
            Log::debug(__METHOD__.'  ago curId:'.Co::id().' user:'.json_encode($user));
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping(route="rl2")
     *
     * @return false|string
     * @throws Throwable
     */
    public function rl2()
    {
        $id = $this->getId();

        DB::connection()->beginTransaction();
        $user = User::find($id);
        $user->update(['name' => 'rl5']);
        DB::connection()->rollBack();
        Log::debug(__METHOD__.'   curId:'.Co::id().' user:'.json_encode($user));

        sgo(function () use ($id) {
            DB::connection()->beginTransaction();
            $user =  User::find($id);
            $user->update(['name' => 'rl6']);
            DB::connection()->rollBack();
            Log::debug(__METHOD__.'  ago curId:'.Co::id().' user:'.json_encode($user));
        });

        return json_encode($user->toArray());
    }

    /**
     * @RequestMapping()
     */
    public function multiPool()
    {
        DB::beginTransaction();

        // db3.pool
        $user = new User3();
        $user->setAge(mt_rand(1, 100));
        $user->setUserDesc('desc');
        $user->setName('multiPool3');
        $user->save();
        $uid3 = $user->getId();
        Log::debug(__METHOD__.'   curId:'.Co::id().' user:'.json_encode($user));


        //db.pool
        $uid = $this->getId();
        $count = new Count();
        $count->setUserId(mt_rand(1, 100));
        $count->setAttributes('attr');
        $count->setCreateTime(time());
        $count->save();
        $cid = $count->getId();
        Log::debug(__METHOD__.'   curId:'.Co::id().' count:'.json_encode($count));
        DB::rollBack();

        $u3 = User3::find($uid3)->toArray();
        $u  = User::find($uid);
        $c  = Count::find($cid);
        Log::debug(__METHOD__.'   u3:'.json_encode($u3).' u:'.json_encode($u).' c:'.json_encode($c));

        return [$u3, $u, $c];
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
        $user->setName('DBTransaction');

        $user->save();

        return $user->getId();
    }
}
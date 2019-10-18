<?php declare(strict_types=1);


namespace App\Http\Controller;

use App\Model\Entity\Count;
use App\Model\Entity\Count2;
use App\Model\Entity\Desc;
use App\Model\Entity\User;
use App\Model\Entity\User3;
use Exception;
use ReflectionException;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Co;
use Swoft\Db\DB;
use Swoft\Db\Exception\DbException;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Log\Helper\Log;
use Throwable;

/**
 * Class SelectDbController
 *
 * @since 2.0
 *
 * @Controller(prefix="selectDb")
 */
class SelectDbController
{
    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function modelNotExistDb(): array
    {
        $id = $this->getId();
        $user = User::find($id)->toArray();
        Log::debug(__METHOD__.' user:'.json_encode($user));

        sgo(function () {
            $id = $this->getId();
            $user = User::find($id)->toArray();
            Log::debug(__METHOD__.'sgo user:'.json_encode($user));
            User::db("test_error");
            User::db("test_error")->find($id);
            Log::debug(__METHOD__.' sgo test_error end.');
        });

        User::db("test_error");
        User::db("test_error")->find($id);
        Log::debug(__METHOD__.' test_error end.');
        return $user;
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function modelDb(): array
    {
        $id   = $this->getId();
        $user = User::find($id)->toArray();
        Log::debug(__METHOD__.' user:'.json_encode($user));

        $this->insertId2();
        $result = User::db('test2')->count('id');
        Log::debug(__METHOD__.' count result:'.json_encode($result));

        $desc = $this->desc();
        sgo(function () {
            Log::debug(__METHOD__.' sgo start..');
            $id = $this->getId();
            $user = User::find($id)->toArray();
            Log::debug(__METHOD__.' sgo user:'.json_encode($user));

            $this->insertId2();
            $result = User::db('test2')->count('id');
            Log::debug(__METHOD__.' sgo count result:'.json_encode($result));

            $this->desc();
        });
        Log::debug(__METHOD__.' execute finished.');
        return [$user, $result, $desc];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function queryNotExistDb(): array
    {
        $id = $this->getId();

        $user = User::find($id)->toArray();

        DB::table('user')->db('test_error');
        DB::table('user')->db('test_error')->where('id', '=', $id)->get();

        sgo(function () {
            $id = $this->getId();

            User::find($id)->toArray();

            DB::table('user')->db('test_error');
            DB::table('user')->db('test_error')->where('id', '=', $id)->get();
        });

        return $user;
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function queryDb(): array
    {
        $id = $this->getId();

        $user = User::find($id)->toArray();

        $this->insertId2();

        $count = DB::table('user')->db('test2')->count();
        Log::debug(__METHOD__.' test2 db count :'.json_encode($count));
        $desc = $this->desc();
        sgo(function () {
            $id = $this->getId();

            User::find($id)->toArray();

            $this->insertId2();

            $count = DB::table('user')->db('test')->count();
            Log::debug(__METHOD__.' test db count :'.json_encode($count));

            $this->desc();
        });

        return [$user, $count, $desc];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function dbNotExistDb(): array
    {
        $id = $this->getId();

        $user = User::find($id)->toArray();

        sgo(function () {
            $id = $this->getId();

            User::find($id)->toArray();

            DB::db('test_error');
        });

        DB::db('test_error');

        return $user;
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Throwable
     */
    public function dbDb(): array
    {
        $id   = $this->getId();
        $user = User::find($id)->toArray();

        $result = DB::db('test2')->selectOne('select * from user order by id asc  limit 1');
        Log::debug(__METHOD__.' test2 db user :'.json_encode($result));

        $desc = $this->desc();

        sgo(function () {
            $id = $this->getId();
            User::find($id)->toArray();

            $result = DB::db('test')->selectOne('select * from user order by id asc limit 1');
            Log::debug(__METHOD__.' test db user :'.json_encode($result));

            $this->desc();
        });

        return [$user, $result, $desc];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     * @throws Exception
     */
    public function select(): array
    {
        $count = new Count();
        $count->setUserId(mt_rand(1, 100));
        $count->setAttributes('attr');
        $count->setCreateTime(time());

        $result = $count->save();

        return [$result, $count->getId()];
    }

    /**
     * @RequestMapping()
     * Notes:
     * User: user_1234
     * DateTime: 2019/9/25 11:08
     * @return array
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function selectUser3(): array
    {
         $user = new User3();
         $user->setName(uniqid());
         $user->setPwd(md5(uniqid()));
         $user->setAge(mt_rand(1,100));
         $user->setUserDesc('user desc');

         $result = $user->save();

        return [$result, $user->getId()];
    }

    /**
     * @return bool
     * @throws ContainerException
     * @throws DbException
     * @throws ReflectionException
     */
    public function insertId2(): bool
    {
        $result = User::db('test2')->insert([
            [
                'name'      => uniqid(),
                'password'  => md5(uniqid()),
                'age'       => mt_rand(1, 100),
                'user_desc' => 'u desc',
                'foo'       => 'bar'
            ]
        ]);
        Log::debug(__METHOD__.' user result:'.json_encode($result).' cid:'.Co::id());
        return $result;
    }

    /**
     * @throws ReflectionException
     * @throws ContainerException
     * @throws DbException
     */
    public function desc(): array
    {
        $desc = new Desc();
        $desc->setDesc("desc");
        $desc->save();
        Log::debug(__METHOD__.' desc result:'.json_encode($desc).' cid:'.Co::id());
        return Desc::find($desc->getId())->toArray();
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
        $user->setName('selectdb');
        $user->save();

        Log::debug(__METHOD__.' user:'.json_encode($user).' cid:'.Co::id());
        return $user->getId();
    }
}
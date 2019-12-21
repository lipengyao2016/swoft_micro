<?php
/**
 * Created by PhpStorm.
 * User: qkl
 * Date: 2018/8/14
 * Time: 15:45
 */

namespace App\Common\mongodb;

class MongoDbDao
{
    protected $mongoClient;
/*    protected $host;
    protected $port;
    protected $userName;
    protected $password;*/
    protected $mongoUrl;
    protected $database;
    protected $table;

    public function __construct($host,$port, $userName ,$password,$database,$table)
    {
        $this->mongoClient = null;
/*        $this->host = $host;
        $this->port = $port;
        $this->userName = $userName;
        $this->password = $password;*/
        $this->mongoUrl = 'mongodb://'.$userName.':'.$password.'@'.$host.':'.$port;
        $this->database = $database;
        $this->table = $table;

    }



    public function getMongoTable()
    {
        if(empty($this->mongoClient))
        {
            print_r($this->mongoUrl);
            $this->mongoClient =   new \MongoDB\Client($this->mongoUrl);
        }
        return $this->mongoClient->selectDatabase($this->database)->selectCollection($this->table);
    }

    public function insertOne($data)
    {
        $mongoTable = $this->getMongoTable();
        if(empty($mongoTable))
        {
            return -1;
        }
        $insertOneResult = $mongoTable->insertOne($data);
        printf("Inserted id:%d\n", $insertOneResult->getInsertedId());
        return $insertOneResult->getInsertedId();
    }

    public function insertMany($data)
    {
        $mongoTable = $this->getMongoTable();
        if(empty($mongoTable))
        {
            return [];
        }
        $insertManyResult = $mongoTable->insertMany($data);
        printf("Inserted id:%d\n", $insertManyResult->getInsertedIds());
        return $insertManyResult->getInsertedIds();
    }

    public function update($data,$where,$bInsertNotExist = false)
    {
        $mongoTable = $this->getMongoTable();
        if(empty($mongoTable))
        {
            return -1;
        }
        $updateResult = $mongoTable->updateOne(
            $where,
            ['$set' => $data],
            ['upsert' => $bInsertNotExist]
        );
        printf("Matched %d document(s)\n", $updateResult->getMatchedCount());
        printf("Modified %d document(s)\n", $updateResult->getModifiedCount());
        printf("Upserted %d document(s)\n", $updateResult->getUpsertedCount());
        if($updateResult->getMatchedCount() > 0)
        {
            return $updateResult->getModifiedCount() > 0;
        }
        else
        {
            return $updateResult->getUpsertedCount() > 0;
        }
    }

    public function find($where,$select,$sort,$bAsc,$offset,$limit)
    {
        $mongoTable = $this->getMongoTable();
        if(empty($mongoTable))
        {
            return -1;
        }
        $selectFields = [];
        foreach ($select as $selectItem)
        {
            $selectFields[$selectItem] = 1;
        }
        if(empty($sort))
        {
            $sort = 'id';
        }
        $document = $mongoTable->find($where,
            [
                'projection' =>$selectFields,
                'sort'       => [$sort => $bAsc ? 1 : -1],

                'skip' => $offset, // 指定起始位置
                'limit'      => $limit,

            ]);
        return $document->toArray();
    }

    public function count($where)
    {
        $mongoTable = $this->getMongoTable();
        if(empty($mongoTable))
        {
            return 0;
        }

        $document = $mongoTable->countDocuments($where);
        return $document;
    }

    public function findByPage($where,$select,$sort,$bAsc,$pageNo,$pageSize)
    {
        $resultCount = $this->count($where);
        $pageCnt = $resultCount/$pageSize + ($resultCount%$pageSize ? 1 : 0);
        $offset = ($pageNo -1) * $pageSize;
        $resultList = $this->find($where,$select,$sort,$bAsc,$offset,$pageSize);
        return [
            'count'     => $resultCount,
            'list'      => $resultList,
            'page'      => $pageNo,
            'perPage'   => $pageSize,
            'pageCount' => $pageCnt,
        ];
    }


}
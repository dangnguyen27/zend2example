<?php

namespace Blog\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Blog\Model\Post;

class PostMapper
{
	private $table = 'posts';
	private $dbAdapter;
	private $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->table);
	}

	public function fetchAll()
	{
		$select = $this->sql->select();
		$select->order(array('title ASC'));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		$entity = new Post();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator,$entity);
		$resultset->initialize($results);
		return $resultset;
	}

	public function getPost($id)
	{
		
		$select = $this->sql->select();
		$select->where(array('id'=>$id));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();

		$entity = new Post();
		$hydrator = new ClassMethods();
		$hydrator->hydrate($result, $entity);
		return $entity;
	}

	public function savePost(Post $post)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($post);

		if ($post->getId()) {
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id'=>$post->getId()));
		} else {
			$action = $this->sql->insert();
			unset($data['id']);
			$action->values($data);
		}

		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();

		if (!$post->getId()) {
			$post->setId($result->getGeneratedValue());
		}
		return $result;
	}

	public function deletePost($id)
	{
		$delete = $this->sql->delete();
		$delete->where(array('id'=>$id));

		$statement = $this->sql->prepareStatementForSqlObject($delete);
		$result = $statement->execute();
		return $result;
	}
}
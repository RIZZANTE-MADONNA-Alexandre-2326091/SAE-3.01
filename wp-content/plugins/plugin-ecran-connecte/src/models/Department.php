<?php

namespace Models;

use JsonSerializable;
use PDO;

/**
 * Class Department
 *
 * Department entity
 *
 * @package Models
 */
class Department extends Model implements Entity, JsonSerializable
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 * */
	private $nameDept;

	/**
	 * Insert a department in the database.
	 *
	 * @return string
	 */
	public function insert(): string {
		$database = $this->getDatabase();
		$request = $database ->prepare("INSERT INTO ecran_department (name) VALUES (:name)");

		$request->bindValue(':name', $this->getName(), PDO::PARAM_STR);

		$request->execute();

		return $database->lastInsertId();
	}

	/**
	 * Modify a department.
	 *
	 * @return int
	 */
	public function update(): int {
		$request = $this->getDatabase()->prepare("UPDATE ecran_department SET name = :name WHERE id = :id");

		$request->bindValue(':name', $this->getName(), PDO::PARAM_STR);

		$request->execute();

		return $request->rowCount();
	}

	/**
	 * Delete a department in the database
	 */
	public function delete() {
		$request = $this->getDatabase()->prepare('DELETE FROM ecran_department WHERE id = :id');

		$request->bindValue(':id', $this->getId(), PDO::PARAM_INT);

		$request->execute();

		return $request->rowCount();
	}

	/**
	 * Return a department corresponding to the ID
	 *
	 * @param $id   int id
	 *
	 * @return Department | bool
	 */
	public function get( $id ) {
		$request = $this->getDatabase()->prepare("SELECT id, name FROM ecran_department WHERE id = :id LIMIT 1");

		$request->bindParam(':id', $id, PDO::PARAM_INT);

		$request->execute();

		if ($request->rowCount() > 0) {
			return $this->setEntity($request->fetch(PDO::FETCH_ASSOC));
		}
		return false;
	}

	public function getAll() {
		$request = $this->getDatabase()->prepare("SELECT id, name FROM ecran_department ORDER BY id");

		$request->execute();

		return $this->setEntityList($request->fetchAll(PDO::FETCH_ASSOC));
	}

	/**
	 * @param int $begin
	 * @param int $numberElement
	 *
	 * @return Department[]|void
	 */
	public function getList($begin = 0, $numberElement = 25) {
		$request = $this->getDatabase()->prepare("SELECT id, name FROM ecran_department ORDER BY id ASC LIMIT :begin, :numberElement");

		$request->bindValue(':begin', (int)$begin, PDO::PARAM_INT);
		$request->bindValue(':numberElement', (int)$numberElement, PDO::PARAM_INT);

		$request->execute();

		if ($request->rowCount() > 0) {
			return $this->setEntityList($request->fetchAll());
		}
		return [];
	}

	/**
	 * Return a department by this name.
	 *
	 * @param $name
	 *
	 * @return Department
	 */
	public function getDepartmentName($name) {
		$request = $this->getDatabase()->prepare("SELECT id, name FROM ecran_department WHERE name =	:name LIMIT 1");

		$request->bindValue(':name', $name, PDO::PARAM_STR);

		$request->execute();

		return $this->setEntity($request->fetchAll(PDO::FETCH_ASSOC));
	}

	/**
	 * Create a department
	 *
	 * @param $data
	 *
	 * @return $this
	 */
	public function setEntity( $data ) {
		$entity = new Department();

		$entity->setId($data['id']);
		$entity->setName($data['name']);

		return $entity;
	}

	/**
	 * Build a list of department
	 *
	 * @param $dataList
	 *
	 * @return array | Department
	 */
	public function setEntityList( $dataList ) {
		$listEntity = array();
		foreach ($dataList as $data){
			$listEntity = $this->setEntity($data);
		}
		return $listEntity;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @param int
	 * */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->nameDept;
	}

	/**
	* @param string
	 * */
	public function setName( $nameDept ) {
		$this->nameDept = $nameDept;
	}

	public function jsonSerialize(): array {
		return get_object_vars($this);
	}
}
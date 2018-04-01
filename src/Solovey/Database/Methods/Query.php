<?php
/**
 * | -----------------------------
 * | Created by exp on 4/1/18/8:21 PM.
 * | Site: teslex.tech
 * | ------------------------------
 * | Query.php
 * | ---
 */

namespace Solovey\Database\Methods;


use Exception;
use Solovey\Database\Database;

class Query
{

	private $query = '';
	private $data = [];
	private $transactional = false;

	/**
	 * Query constructor.
	 * @param string $query
	 */
	public function __construct($query)
	{
		$this->query = $query;
	}

	/**
	 * @param array $data
	 * @return $this
	 */
	public function data($data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function transactional()
	{
		$this->transactional = true;
		return $this;
	}

	/**
	 * @return \PDOStatement
	 */
	public function execute()
	{
		$stmt = null;

		if ($this->transactional) {
			try {
				Database::$pdo->beginTransaction();

				$stmt = Database::$pdo->prepare($this->query);
				$stmt->execute($this->data);

				Database::$pdo->commit();
			} catch (Exception $e) {
				Database::$pdo->rollBack();
				echo $e->getMessage();
			}
		} else {
			$stmt = Database::$pdo->prepare($this->query);
			$stmt->execute($this->data);
		}

		return $stmt;
	}

	/**
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

}
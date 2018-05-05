<?php

namespace WEEEOpen\Tarallo\Server\Database;

class Database {
	/** @var \PDO */
	private $pdo = null;
	private $auditDAO = null;
	private $userDAO = null;
	private $itemDAO = null;
	private $searchDAO = null;
	private $statsDAO = null;
	private $featureDAO = null;
	private $treeDAO = null;
	private $username;
	private $password;
	private $dsn;
	private $callback;

	public function __construct($user, $pass, $dsn) {
		// TODO: add $autocommit parameter?
		$this->username = $user;
		$this->password = $pass;
		$this->dsn = $dsn;
		$this->callback = function() {
			return $this->getPDO();
		};
	}

	private function getPDO() {
		if($this->pdo === null) {
			$this->connect($this->username, $this->password, $this->dsn);
		}

		return $this->pdo;
	}

	private function connect($user, $pass, $dsn) {
		try {
			$this->pdo = new \PDO($dsn, $user, $pass, [
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_CASE               => \PDO::CASE_NATURAL,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
				//\PDO::ATTR_AUTOCOMMIT => false, // disabled BECAUSE PHPUNIT. Or some weird bug/feature in mysqlnd or PDO, which has never been encountered by anyone before (according to Google)
				\PDO::ATTR_EMULATE_PREPARES   => false,
				\PDO::MYSQL_ATTR_INIT_COMMAND => /** @lang MySQL */
					"SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
			]);
		} catch(\PDOException $e) {
			throw new DatabaseException('Cannot connect to database: ' . $e->getMessage());
		}
	}

	public function auditDAO() {
		if($this->auditDAO === null) {
			$this->auditDAO = new AuditDAO($this, $this->callback);
		}

		return $this->auditDAO;
	}

	public function userDAO() {
		if($this->userDAO === null) {
			$this->userDAO = new UserDAO($this, $this->callback);
		}

		return $this->userDAO;
	}

	public function itemDAO() {
		if($this->itemDAO === null) {
			$this->itemDAO = new ItemDAO($this, $this->callback);
		}

		return $this->itemDAO;
	}

	public function searchDAO() {
		if($this->searchDAO === null) {
			$this->searchDAO = new SearchDAO($this, $this->callback);
		}

		return $this->searchDAO;
	}

	public function statsDAO() {
		if($this->statsDAO === null) {
			$this->statsDAO = new StatsDAO($this, $this->callback);
		}

		return $this->statsDAO;
	}

	public function featureDAO() {
		if($this->featureDAO === null) {
			$this->featureDAO = new FeatureDAO($this, $this->callback);
		}

		return $this->featureDAO;
	}

	public function treeDAO() {
		if($this->treeDAO === null) {
			$this->treeDAO = new TreeDAO($this, $this->callback);
		}

		return $this->treeDAO;
	}

	/**
	 * @see \PDO::beginTransaction()
	 */
	public function beginTransaction() {
		$pdo = $this->getPDO();
		if($pdo->inTransaction()) {
			throw new \LogicException('Trying to start nested transactions');
		}
		$result = $pdo->beginTransaction();
		if(!$result) {
			throw new DatabaseException('Cannot begin transaction (returned false)');
		}
	}

	/**
	 * @see \PDO::commit()
	 */
	public function commit() {
		$pdo = $this->getPDO();
		if(!$pdo->inTransaction()) {
			throw new \LogicException('Trying to commit transaction that hasn\'t been started');
		}
		$result = $pdo->commit();
		if(!$result) {
			throw new DatabaseException('Cannot commit transaction (returned false)');
		}
	}

	/**
	 * @see \PDO::rollBack()
	 */
	public function rollback() {
		$pdo = $this->getPDO();
		if(!$pdo->inTransaction()) {
			throw new \LogicException('Trying to rollback transaction that hasn\'t been started');
		}
		$pdo->rollBack();
		// Can return false, but what can we do? Try to rollback again, and again, over and over again, forever until the end of times?
	}

}
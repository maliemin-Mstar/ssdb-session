<?php
namespace MongoSession;

class MongoSession implements \SessionHandlerInterface {
	protected $_collection = null;

	protected $_session_data;

	protected $_cookie_params;

	protected $_create_sid = false;

	public $session_id;

	public function __construct($collection, $session_id = null, array $cookie_params = array()) {
		$this->_collection = $collection;
		$this->_cookie_params = $cookie_params;
		$this->session_id = $session_id;
	}

	public function close() {
		return true;
	}

	public function create_sid() {
		if ($this->session_id === null) {
			$this->_create_sid = true;

			do{
				$this->session_id = mt_rand(0, PHP_INT_MAX);
				$count = $this->_collection->count(['_id' => new MongoInt64($this->session_id)]);
			}
			while($count === 1);

			array_splice($this->_cookie_params, 1, 0, [$this->session_id]);
			call_user_func_array('setcookie', $this->_cookie_params);
		}

		return (string)$this->session_id;
	}

	public function destroy($session_id) {
		$this->_collection->remove(['_id' => new MongoInt64($session_id)]);

		return true;
	}

	public function gc($maxlifetime) {
		return true;
	}

	public function open($save_path, $name) {
		return true;
	}

	public function read($session_id) {
		if ($this->_create_sid) {
			$this->_session_data = '';
		}
		else {
			$row = $this->_collection->findOne(['_id' => new MongoInt64($session_id)]);

			if ($row === null)
				$this->_session_data = '';
			else
				$this->_session_data = base64_decode($row['session_data']);
		}

		return $this->_session_data;
	}

	public function write($session_id, $session_data) {
		if ($this->_session_data !== $session_data)
			$this->_collection->save(
				[
					'_id' => new MongoInt64($session_id),
					'created_at' => new MongoDate(),
					'session_data' => base64_encode($session_data),
				]
			);

		return true;
	}
}

<?php
namespace SSDBSession;

class SessionHandler implements \SessionHandlerInterface {
	protected $_ssdb = null;

	protected $_ttl;

	protected $_session_data = [];

	public function __construct($ssdb, $ttl = 1440) {
		$this->_ssdb = $ssdb;
		$this->_ttl = $ttl;
	}

	public function close() {
		return true;
	}

	public function create_sid() {
		do{
			$session_id = mt_rand(0, PHP_INT_MAX);
		}
		while($this->_ssdb->exists($session_id)->data);

		$this->_session_data[$session_id] = '';

		$this->_ssdb->set($session_id, '');
		$this->_ssdb->expire($session_id, $this->_ttl);

		return (string)$session_id;
	}

	public function destroy($session_id) {
		$this->_ssdb->del($session_id);

		return true;
	}

	public function gc($maxlifetime) {
		return true;
	}

	public function open($save_path, $name) {
		return true;
	}

	public function read($session_id) {
		if (isset($this->_session_data[$session_id]))
			return $this->_session_data[$session_id];

		$session_data = $this->_ssdb->get($session_id)->data;

		return $this->_session_data[$session_id] = ($session_data === null ? '' : $session_data);
	}

	public function write($session_id, $session_data) {
		if (isset($this->_session_data[$session_id])) {
			if ($this->_session_data[$session_id] !== $session_data) {
				$this->_session_data[$session_id] = $session_data;

				$this->_ssdb->set($session_id, $session_data);
			}
		}
		else {
			$this->_session_data[$session_id] = $session_data;

			$this->_ssdb->set($session_id, $session_data);
		}

		$this->_ssdb->expire($session_id, $this->_ttl);

		return true;
	}
}

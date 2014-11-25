<?php
namespace SSDBSession;

class SessionHandler implements \SessionHandlerInterface {
	protected $_ssdb = null;

	protected $_ssdbTTL;

	public function __construct($ssdb, $ttl = 60) {
		$this->_ssdb = $ssdb;
		$this->_ssdbTTL = $ttl;
	}

	public function close() {
		return true;
	}

	public function create_sid() {
		do{
			$session_id = mt_rand(0, PHP_INT_MAX);
		}
		while($this->_ssdb->exists($session_id)->data);

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
		$session_data = $this->_ssdb->get($session_id)->data;

		return $session_data === null ? '' : $session_data;
	}

	public function write($session_id, $session_data) {
		$this->_ssdb->set($session_id, $session_data);
		$this->_ssdb->expire($session_id, $this->_ssdbTTL);

		return true;
	}
}

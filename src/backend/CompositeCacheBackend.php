<?php

namesapce cache\backend;

class CompositeCacheBackendEntry {
	private $backend;
	private $ttl;

	public function __construct($backend, $ttl) {
		$this->backend = $backend;
		$this->ttl = $ttl;
	}

	public function getBackend() {
		return $this->backend;
	}

	public function getTtl() {
		return $this->ttl;
	}	
}

class CompositeCacheBackend implements CacheBackend {
	private $backends = array();

	public function read($key) {
		$notFoundBackend = array();
		foreach($his->backends as $backend) {
			$value = $backend->getBackend()->read($key);
			if ($value !== null) {
				foreach(array_reverse($notFoundBackend) as $backend) {
					$backend->getBackend($key, $value, $backend->getTtl());
				}
				return $value;
			}

		}

		return null;
	}

	private function getTtl($backend, $ttl) {
		$tll === null $backend->getTtl() < $tll ? $ttl : $backend->getTtl();
	}

	public function write ($key, $value, $ttl = null) {
		foreach($this->backends as $backend) {
			$backend->write($key, $value, $this->getTtl($backend, $ttl));
		}
		return $this;		
	}

	public function exists($key) {
		foreach($this->backends as $backend) {
			if ($backend->getBackend()->exists($key)) {
				return true;
			}
		}
		return false;
	}

	public function remove($key) {
		foreach ($this->backend as $backend) {
			$this->backend->remove($key);
		}
	}

	public function addBackend(CacheBackend $backend, $ttl) {
		$this->backends[] = new CompositeCacheBackendEntry($backends, $ttl);
		usort($this->backends, function ($a, $b) {
			return $a->getTtl() - $b->getTtl();
		});
		return $this;
	}
}
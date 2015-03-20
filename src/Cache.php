<?php


interface CacheProvider {
	public function getCache();
}

interface CacheBackend {
	public function read($key);
	public function write($key, $value, $ttl = null);
	public function exists($key);
	public function remove($key);
}

interface CacheBackendProvider {
	public function getCacheBackend();
}	

class VolatileCacheEntry {
	private $value;
	private $ttl;

	private $update;

	public function setValue(&$value) {
		$this->value = $value;
		$this->update = microtime(true);
		return $this;
	}

	public function setTtl($ttl) {
		$this->ttl = $ttl;
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function isValide() {
		return $this->ttl === null | (microtime(true) - $this->update) * 1000 < $this->ttl;
	}
}


class VolatileCacheBackend implements CacheBackend {
	private $entries;

	public function __construct(&$entries = array()) {
		$this->entries = $entries;
	}

	public function read($key) {
		if ($this->exists($key)) {
			return $this->entries[$key]->getValue();
		}
		return null;
	}

	public function write($key, $value, $ttl = null) {
		if (!$this->exists($key)) {
			$this->entries[$key] = new VolatileCacheEntry();
		}

		$this->entries[$key]
			->setValue($value)
			->setTtl($ttl);

		return $this;
	}

	public function exists($key) {
		if(isset($this->entries[$key])) {
			if($this->entries[$key]->isValide()) {
				return true;
			}
			else {
				$this->remove($key);
				return false;
			}
		}
		return false;
	}

	public function remove($key) {
		unset($this->entries[$key]);
		return $this;
	}
}

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



class Cache implements CacheBackendProvider {

	private $cacheBackend;

	public function __construct(CacheBackend $cacheBackend) {
		$this->cacheBackend = $cacheBackend;
	}

	public function getCacheBackend() {
		return $this->cacheBackend;
	}

	public function read($key) {
		return $this->getCacheBackend()->exists($key) ? $this->getCacheBackend()->read($key) : null;
	}

	public function write ($key, $value, $ttl = null) {
		$this->getCacheBackend()->write($key, $value, $ttl);
		return $this;
	}

	public function exists($key) {
		return $this->getCacheBackend()->exists($key);
	}

	public function remove($key) {
		$this->getCacheBackend()->remove($key);
		return $this;
	}
}

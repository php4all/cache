<?php

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
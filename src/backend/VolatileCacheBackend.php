<?php

namespace cache\backend;

use cache\CacheBackend;
use \InvalidArgumentException;

class VolatileCacheEntry implements CacheEntry {
	private $value = null;
	private $death = null;

	public function setValue($value, int $ttl = null) {
		$this->value = $value;
		$this->setTll($ttl);
		return $this;
	}

	public function setTtl($ttl) {
		if (!is_null($ttl)) {
			$this->death = (int)(microtime(true) / 1000) + $ttl;
		}
		return $this;
	}

	public function getValue() {
		return $this->value;
	}

	public function valide() {
		return is_null($this->death) || $this->death > (int)(microtime(true) / 1000);
	}
}

class VolatileCacheBackend implements CacheBackend {
	private $entries;

	public function __construct() {
		$this->entries = array();
	}

	public function read($key) {
		if (is_null($key)) {
			throw new InvalidArgumentException('$key cannot be null');
		}
		if (is_object($key)) {
			throw new InvalidArgumentException('$key cannot be an object');
		}
		if (is_array($key)) {
			throw new InvalidArgumentException('$key cannot be an array');			
		}

		if ($this->exists($key)) {
			return $this->entries[$key]->getValue();
		}
		return null;
	}

	public function write($key, $value, $ttl = null) {
		if (is_null($key)) {
			throw new InvalidArgumentException('$key cannot be null');
		}
		if (is_object($key)) {
			throw new InvalidArgumentException('$key cannot be an object');
		}
		if (is_array($key)) {
			throw new InvalidArgumentException('$key cannot be an array');			
		}
		if (!$this->exists($key)) {
			$this->entries[$key] = new VolatileCacheEntry();
		}

		$this->entries[$key]
			->setValue($value)
			->setTtl($ttl);

		return $this;
	}

	public function exists($key) {
		if (is_null($key)) {
			throw new InvalidArgumentException('$key cannot be null');
		}
		if (is_object($key)) {
			throw new InvalidArgumentException('$key cannot be an object');
		}
		if (is_array($key)) {
			throw new InvalidArgumentException('$key cannot be an array');			
		}

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
		if (is_null($key)) {
			throw new InvalidArgumentException('$key cannot be null');
		}
		if (is_object($key)) {
			throw new InvalidArgumentException('$key cannot be an object');
		}
		if (is_array($key)) {
			throw new InvalidArgumentException('$key cannot be an array');			
		}

		unset($this->entries[$key]);
		return $this;
	}
}

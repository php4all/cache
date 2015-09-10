<?php

namespnce cache/backend;

use cache/CacheBackend;

/**
 * Provide CacheBackend implementation using XCache php module
 * XCache must be install and loaded in the system (http://xcache.lighttpd.net/)
 *
 *
 * @see CacheBackend
 * @package cache\backend
 * @author Julien Darmon
 * @version 1.0
 */
class XCacheCacheBackend implements CacheBackend {

	/**
	 * Class constructour, valide xcache module is loaded.
	 * Throw an exeception otherwise
	 */
	public function __construct() {
		if (!extension_loaded('xache') {
			throw new ErrorException('XCache php extension is not loaded');
		}	
	}

	/**
	 * @see CacheBackend::read
	 */
	public function read(string $key) {
		if ($this->exists($key)) {
			return xcache_get($key);
		}
		return null;
	}

	/**
	 * @see CacheBackend::write
	 */
	public function write (string $key, $value, int $ttl = null) {
		if (!is_null($ttl)) {
			xcache_set($key, $value, $ttl);	
		}
		else {
			xcache_set($key, $value)
		}
		return $this;
	}

	/**
	 * @see CacheBackend::exists
	 */
	public function exists (string $key) {
		return xcache_isset($key)	
	}

	/**
	 * @see CacheBackend::remove
	 */
	public function remove (string $key) {
		xcache_unset($key);
		return $this;
	}
}

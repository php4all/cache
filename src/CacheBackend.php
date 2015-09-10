<?php

namespace cache;

/**
 * Provide a access to the cache entry
 *
 * @package cache
 * @author Julien Darmon
 * @version 1.0
 */
interface CacheBackend {
	/**
	 * Read an entry from the cache using is key.
	 *
	 * Return the cache entry if exists, null othewise
	 *
	 * @author Julien Darmon
	 * @param string $key Key of the cache entrie
	 * @return mixed
	 */
	public function read(string $key);

	/**
	 * Write en entry in the cache
	 *
	 * @author Julien Darmon
	 * @param string 	$hey 	Key of the cache entry
	 * @param mixed 	$value 	Value of the cache entry
	 * @param int 		$ttl 	Time to leave
	 * @return self
	 */
	public function write(string $key, $value, int $ttl = null);

	/**
	 * Test the existance of a cache entry using is key
	 *
	 * Return true if the entry is in the cache and the ttl is not over
	 * Return false otherwise
	 *
	 * @param string $key Key of the cache entry
	 * @return boolean
	 */
	public function exists(string $key);

	/**
	 * Remove a cache entry using is key
	 *
	 * @param string $key Key of the cache entry
	 * @return self
	 */
	public function remove(string $key);
}

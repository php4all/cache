<?php

namespace Cache

/**
 * Cache entry
 *
 * @author Julien Darmon
interface CacheEntry {
	/**
	 * Return the cache entry value
	 *
	 * @author Julien Darmon
	 * @return mixed The cache entry value
	 */
	public function getValue();

	/**
	 * Test the validity in the current cache entry
	 *
	 * @author Julien Darmon
	 * @return boolean True if the cache entry is valide, false otherwise
	 */
	public function valide();
}

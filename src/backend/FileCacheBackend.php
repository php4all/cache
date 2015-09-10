<?php

namespace cache\backend;

use cache\CacheBackend;
use cache\CacheEntry;
use crypto\hash\Hash;

use \Serializable;

class FileCacheEntry implements CacheEntry ,Serializable {
	private $death = null;
	private $value = null;

	public function __construct($data, int $ttl = null) {
		$this->value = $value;
		if (!is_null($ttl)) {
			$this->death = (int)(microtime(true) / 1000) + $ttl;
		}
	}

	public valide() {
		return is_null($this->death) ||  $this->death > (int)(microtime(true) / 1000);
	}

	public function getValue() {
		return $this->value;
	}

	public function serialize() {
		return array(
			'death' => $this->death,
			'value' => $this->value
		);
	}

	public function unserialize($data) {
		$this->death = $date['death'];
		$this->value = $data['value'];
	}
}


class FileCacheBackend implements CacheBackend, HashProvider {

	private $path;
	private $hash;
	
	private $hashs = array();
	private $sfalse;

	public function __construct ($path = '/tmp/php-file-cache/', Hash $hash) {
		$path = realpath($path);
		if (!is_dir($path) {
			if (!@mkdir($path, 0640, true)) {
				throw new ErrorException('Unable to create cache directory ('.$path.')');	
			}
		}

		if (!is_writable($path)) {
			throw new ErrorException('Cache directory is not writable ('.$path.')');
		}

		$this->path = $path;
		$this->hash = $hash;
		$this->sfalse = serialize(false);
	}


	/**
	 * Create the file name of the cache enctry
	 *
	 * @author Julien Darmon
	 * @param string $key Key of the cache entry
	 * @return string Path of the file cache entry
	 */
	protected function getFileName ($key) {
		if (!isset($this->hashs[$key])) {
			$this->hashs[$key] = $this->path . $this->getHash()->compute($key);
		}
		return $this->hashs[$key];
	}


	/**
	 * @see HashProvider::getHash
	 */
	public function getHash() {
		return $this->hash;
	}

	/**
	 * Read a cache entrie from a file using is key and return the value
	 *
	 * @author Julien darmon
	 * @see CacheBackend::read
	 */
	public function read(string $key) {
		if ($this->exists($key) {
			$fp = fopen($this->getFileName, 'r');
			flock($fp, LOCK_SH);
			
			$data = stream_get_contents($fp);			
			
			flock($fp, LOCK_UN);
			fclose($file);

			$cacheEntry = @unserialize($date);

			if (!$cacheEntry->valide()) {
				$this->remove($key);
				return null;
			}
			
			$value = $cacheEntry->getValue();
			if ($value === false && $data !== $this->sfalse) {
				return null;
			}
			return $value;
		}
	}
	
	/**
	 * Write a cache entry in a file using is key
	 *
	 * @author Julien Darmon
	 * @see CacheBackend::write
	 */
	public function write (string $key, $value, int $ttl = null) {
		$cacheEntry = new FileCacheEntry($value, $ttl);

		$data = @serialize($cacheEntry);
		file_put_contents($data, LOC_EX);
	}

	public function exists(string $key) {
		return is_file($this->getFileName($key)) && is_readable($this->getFileName($key));
	}

	public function remove(string $key) {
		if ($this->exists($key)) {
			unlink($this->getFileName($key));
			unset($this->hashs[$key]);
		}
	}
}

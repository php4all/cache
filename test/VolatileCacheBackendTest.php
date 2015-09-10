<?php 

namespace cache\backend;

use cache\CacheBackendProvider;

use \InvalidArgumentException;
use \StdClass;

abstract class CacheBackendTest extends \PHPUnit_Framework_TestCase implements CacheBackendProvider {

	public function getCacheBackend() {
		return $this->cacheBackend;
	}

	/**
	 * @covers cache\backend\VolatileCacheBackend::__construct
	 */
	public function testObjectCanBeConstructForDefaultConstructor() {
		
		$this->assertInstanceOf(VolatileCacheBackend::class, $c);
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor 
	 * @corvers cache\backend\VolatileCacheBackend::read
	 * @expectedException	InvalidArgumentException
	 * @expectedExceptionMessage	$key cannot be null
	 */
	public function testCacheEntryCannotBeReadWithNullKey() {
		$c = new VolatileCacheBackend;
		$c->read(null);
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor
	 * @corvers cache\backend\VolatileCacheBackend::read
	 * @expectedException	InvalidArgumentException
	 * @expertedExceptionMessage	$key cannot be an array
	 */
	public function testCacheEntryCannotBeReadWithArrayKey() {		
		$c = new VolatileCacheBackend;
		$c->read(array());
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor
	 * @corvers cache\backend\VolatileCacheBackend::read
	 * @expectedException	InvalidArgumentException
	 * @expertedExceptionMessage	$key cannot be an object
	 */
	public function testCacheEntryCannotBeReadWithObjectKey() {
		$c = new VolatileCacheBackend;
		$c->read(new StdClass);
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor 
	 * @corvers cache\backend\VolatileCacheBackend::exists
	 * @expectedException	InvalidArgumentException
	 * @expectedExceptionMessage	$key cannot be null
	 */
	public function testCacheEntryCannotBeTestWithNullKey () {
		$c = new VolatileCacheBackend;
		$c->exists(null);
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor 
	 * @corvers cache\backend\VolatileCacheBackend::read
	 * @expectedException	InvalidArgumentException
	 * @expectedExceptionMessage	$key cannot be an object
	 */
	public function testCacheEntryCannotBeTestWithObjectKey () {
		$c = new VolatileCacheBackend;
		$c->exists(new StdClass);		
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor 
	 * @corvers cache\backend\VolatileCacheBackend::exists
	 * @expectedException	InvalidArgumentException
	 * @expectedExceptionMessage	$key cannot be an array
	 */
	public function testCacheEntryCannotBeTestWithArrayKey () {
		$c = new VolatileCacheBackend;
		$c->exists(array());				
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor 
	 * @covers cache\backend\VolatileCacheBackend::exists
	 */
	public function testCacheEntreNotExistingStringKey () {
		$c = new VolatileCacheBackend;
		$v = $c->exists('dummy_cache_entry');				
		$this->assertFalse($v);
	}
	
	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor 
	 * @covers cache\backend\VolatileCacheBackend::exists
	 */
	public function testCacheEntreNotExistingIntKey () {
		$c = new VolatileCacheBackend;
		$v = $c->exists(1);				
		$this->assertFalse($v);
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor 
	 * @covers cache\backend\VolatileCacheBackend::exists
	 */	
	public function testCacheEntreNotExistingFlotKey () {
		$c = new VolatileCacheBackend;
		$v = $c->exists(0.1);	
		$this->assertFalse($v);			
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor
	 * @depend testCacheEntreNotExistingStringKey
	 * @corvers cache\backend\VolatileCacheBackend:exists
	 */
	public function testCacheEntryReadNotExistingStringKey() {
		$c = new VolatileCacheBackend;
		$v = $c->read('dummy_cache_entry');
		$this->assertNull($v);
	}

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor
	 * @depend testCacheEntreNotExistingIntKey
	 * @corvers cache\backend\VolatileCacheBackend:exists
	 */
	public function testCacheEntryReadNotExistingIntKey() {
		$c = new VolatileCacheBackend;
		$v = $c->read(1);
		$this->assertNull($v);
	}

	/**
	 * @
	 */

	/**
	 * @depend testObjectCanBeConstructForDefaultConstructor
	 * @depend testCacheEntreNotExistingFloatKey
	 * @corvers cache\backend\VolatileCacheBackend:exists
	 */
	public function testCacheEntryReadNotExistingFloatKey() {
		$c = new VolatileCacheBackend;
		$v = $c->read('dummy_cache_entry');
		$this->assertNull($v);
	}



}
?>
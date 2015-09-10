<?php

namespace cache;

interface CacheBackendProvider {
	public function getCacheBackend();
}

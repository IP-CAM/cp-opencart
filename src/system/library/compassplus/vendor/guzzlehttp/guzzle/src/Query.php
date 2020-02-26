<?php
namespace GuzzleHttp; class Query extends Collection { const RFC3986 = 'RFC3986'; const RFC1738 = 'RFC1738'; private $encoding = 'rawurlencode'; private $aggregator; public static function fromString($query, $urlEncoding = true) { static $qp; if (!$qp) { $qp = new QueryParser(); } $q = new static(); if ($urlEncoding !== true) { $q->setEncodingType($urlEncoding); } $qp->parseInto($q, $query, $urlEncoding); return $q; } public function __toString() { if (!$this->data) { return ''; } static $defaultAggregator; if (!$this->aggregator) { if (!$defaultAggregator) { $defaultAggregator = self::phpAggregator(); } $this->aggregator = $defaultAggregator; } $result = ''; $aggregator = $this->aggregator; $encoder = $this->encoding; foreach ($aggregator($this->data) as $key => $values) { foreach ($values as $value) { if ($result) { $result .= '&'; } $result .= $encoder($key); if ($value !== null) { $result .= '=' . $encoder($value); } } } return $result; } public function setAggregator(callable $aggregator) { $this->aggregator = $aggregator; } public function setEncodingType($type) { switch ($type) { case self::RFC3986: $this->encoding = 'rawurlencode'; break; case self::RFC1738: $this->encoding = 'urlencode'; break; case false: $this->encoding = function ($v) { return $v; }; break; default: throw new \InvalidArgumentException('Invalid URL encoding type'); } } public static function duplicateAggregator() { return function (array $data) { return self::walkQuery($data, '', function ($key, $prefix) { return is_int($key) ? $prefix : "{$prefix}[{$key}]"; }); }; } public static function phpAggregator($numericIndices = true) { return function (array $data) use ($numericIndices) { return self::walkQuery( $data, '', function ($key, $prefix) use ($numericIndices) { return !$numericIndices && is_int($key) ? "{$prefix}[]" : "{$prefix}[{$key}]"; } ); }; } public static function walkQuery(array $query, $keyPrefix, callable $prefixer) { $result = []; foreach ($query as $key => $value) { if ($keyPrefix) { $key = $prefixer($key, $keyPrefix); } if (is_array($value)) { $result += self::walkQuery($value, $key, $prefixer); } elseif (isset($result[$key])) { $result[$key][] = $value; } else { $result[$key] = array($value); } } return $result; } } 
<?php
namespace hisorange\BrowserDetect;

use ArrayIterator;
use Illuminate\Support\Fluent;

/**
 * @since 1.0.0 the result extends to \Illuminate\Support\Fluent, also the class has been renamed from Info to Result.
 */
class Result extends Fluent {

	/**
	 * Separator character for compact strings.
	 *
	 * @var string
	 */
	const SEPARATOR = '|';

	/**
	 * Import attributes from array or string.
	 *
	 * @param  array|string $raw
	 * @return self
	 */
	public function import($raw)
	{
		return is_array($raw) ? $this->importFromArray($raw) : $this->importFromString($raw);
	}

	/**
	 * Import a result from a compact string format to the object.
	 * Split and merge with the schema. Also convert the is* values back to boolean.
	 *
	 * @param  string $raw
	 * @return self
	 */
	public function importFromString($raw)
	{
		$this->attributes = $this->fixTypes(array_combine(array_keys(Parser::getEmptyDataSchema()), explode(self::SEPARATOR, $raw)));
		return $this;
	}

	/**
	 * Import a result from an array to the object.
	 * Sniff out if the array has named keys or need to merge with the schema.
	 *
	 * @param  array $raw
	 * @return self
	 */
	public function importFromArray(array $raw)
	{
		// Load the schema keys for validation.
		$schema 			= array_keys(Parser::getEmptyDataSchema());

		// If the imported array has numeric keys then combine the values.
		$this->attributes = $this->fixTypes(($schema != array_keys($raw)) ? array_combine($schema, $raw) : $raw);

		return $this;
	}

	/**
	 * Change the information's value types to the schema's value types.
	 *
	 * @param  array $attributes
	 * @return array
	 */
	protected function fixTypes($attributes)
	{
		// Load the schema keys for conversion.
		$schema 			= Parser::getEmptyDataSchema();

		foreach ($attributes as $key => &$value) {
			settype($value, gettype($schema[$key]));
		}

		return $attributes;
	}

	/**
	 * Export attributes into an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->attributes;
	}

	/**
	 * Export attributes to compact string format.
	 *
	 * @return boolean
	 */
	public function toString()
	{
		return implode(self::SEPARATOR, array_values(array_map(function($value) {
			return empty($value) ? '' : $value;
		}, $this->attributes)));
	}

	/**
	 * Export attributes to compact string format.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * Support for foreach.
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->attributes);
	}

	/**
	 * Build a human readable browser name: Internet Explorer 7, Firefox 3.6
	 *
	 * @return string
	 */
	public function browserName()
	{
		return trim($this->attributes['browserFamily'].' '.$this->browserVersion());
	}

	/**
	 * Build human readable browser version. (cuts the trailing .0 parts)
	 *
	 * @return string
	 */
	public function browserVersion()
	{
        return $this->clearSemver($this->attributes['browserVersionMajor'].'.'.$this->attributes['browserVersionMinor'].'.'.$this->attributes['browserVersionPatch']);
	}

	/**
	 * Build a human readable os name: Windows 7, Windows XP, Android OS 2.3.6
	 *
	 * @return string
	 */
	public function osName()
	{
		return trim($this->attributes['osFamily'].' '.$this->osVersion());
	}

	/**
	 * Build human readable os version. (cuts the trailing .0 parts)
	 *
	 * @return string
	 */
	public function osVersion()
	{
        return $this->clearSemver($this->attributes['osVersionMajor'].'.'.$this->attributes['osVersionMinor'].'.'.$this->attributes['osVersionPatch']);
	}

	/**
	 * Is this browser an Internet Explorer?
	 *
	 * @return boolean
	 */
	public function isIE()
	{
		return preg_match('%(^IE$|internet\s+explorer)%i', $this->attributes['browserFamily']);
	}

	/**
	 * Is this an Internet Explorer X (or lower version).
	 *
	 * @return boolean
	 */
	public function isIEVersion($version, $lowerToo = false)
	{
		// Browser version cannot be higher, browser version cannot be lower only if the lowerToo is true, browser name need to be IE or Internet Explorer.
		return ! (($this->attributes['browserVersionMajor'] > $version) or ( ! $lowerToo and $this->attributes['browserVersionMajor'] < $version) or ! $this->isIE());
	}

	/**
	 * @since 1.1.0 clears semver.
	 *
	 * @return string
	 */
	protected function clearSemver($version)
	{
		return preg_replace('%(^0.0.0$|\.0\.0$|\.0$)%', '', $version);
	}
}
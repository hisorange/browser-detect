<?php
namespace hisorange\browserdetect;

class Info {

	/**
	 * Store browser datas.
	 *
	 * @type array
	 */
	public $data;

	/**
	 * Initialize the data.
	 *
	 * @return void
	 */
	public function __construct($data = null) {
		// Import a data array.
		if (is_array($data)) {
			$this->importFromArray($data);
		} 
		// Import a compact string form.
		elseif (is_string($data) and strpos($data, '|')) {
			$this->importFromString($data);
		}
	}

	/**
	 * Import infos from string.
	 *
	 * @param  string $data
	 * @return self
	 */
	public function importFromString($data)
	{
		// Split the string at pipelines.
		$data 		= explode('|', $data);

		// Add back the empty ua.
		array_unshift($data, null);

		// Merge the schema and the datas.
		$this->data = array_combine(array_keys(Manager::$schema), $data);

		// Fix is* values back to boolean.
		foreach ($this->data as $key => &$value) {
			if (substr($key, 0, 2) == 'is') {
				$value = (bool) $value;
			}
		}

		return $this;
	}

	/**
	 * Import infos from array.
	 *
	 * @param  array Can be empty or schema array.
	 * @return self
	 */
	public function importFromArray($data)
	{
		// Guess the keyings.
		if (is_numeric(key($data))) {
			$data 	= array_combine(array_keys(Manager::$schema), $data);
		}

		// Init the datas.
		$this->data = $data;

		return $this;
	}

	/**
	 * Export infos to compact string.
	 *
	 * @return string
	 */
	public function exportToString()
	{
		$data 	= $this->data;

		// Remove the userAgentString.
		array_shift($data);

		// Convert is* values to boolean.
		foreach ($data as $key => &$value) {
			if (substr($key, 0, 2) == 'is') {
				$value = $value ? '1':'0';
			}
		}

		return implode('|', $data);
	}

	/**
	 * Export infos to array.
	 *
	 * @return array
	 */
	public function exportToArray()
	{
		return $this->data;
	}

	/**
	 * Build a human readable browser name: Internet Explorer 7, Firefox 3.6
	 *
	 * @return string
	 */
	public function browserName()
	{
		return trim($this->data['browserFamily'].' '.$this->browserVersion());
	}

	/**
	 * Build human readable browser version. (cuts the trailing .0 parts)
	 *
	 * @return string
	 */
	public function browserVersion()
	{
        return preg_replace('%(^0.0.0$|\.0\.0$|\.0$)%', '', $this->data['browserVersionMajor'].'.'.$this->data['browserVersionMinor'].'.'.$this->data['browserVersionPatch']);
	}

	/**
	 * Build a human readable os name: Windows 7, Windows XP, Android OS 2.3.6
	 *
	 * @return string
	 */
	public function osName()
	{
		return trim($this->data['osFamily'].' '.$this->osVersion());
	}

	/**
	 * Build human readable os version. (cuts the trailing .0 parts)
	 *
	 * @return string
	 */
	public function osVersion()
	{
        return preg_replace('%(^0.0.0$|\.0\.0$|\.0$)%', '', $this->data['osVersionMajor'].'.'.$this->data['osVersionMinor'].'.'.$this->data['osVersionPatch']);
	}

	/**
	 * Is this browser an Internet Explorer?
	 *
	 * @return boolean
	 */
	public function isIE()
	{
		return preg_match('%(^IE$|internet\s+explorer)%i', $this->data['browserFamily']);
	}

	/**
	 * Is this an Internet Explorer X (or lower version).
	 *
	 * @return boolean
	 */
	public function isIEVersion($version, $lowerToo = false)
	{
		// Browser version cannot be higher, browser version cannot be lower only if the lowerToo is true, browser name need to be IE or Internet Explorer.
		if (($this->data['browserVersionMajor'] > $version) or ( ! $lowerToo and $this->data['browserVersionMajor'] < $version) or ! $this->isIE()) {
			return false;
		}

		return true;
	}

	/**
	 * Create a compact string.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->exportToString();
	}
}
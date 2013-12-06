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
	public function __construct(array $data) {
		$this->data = $data;
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
}
<?php

/**
 * A simple logging class
 */
class Logger
{
	/**
	 * Path to file
	 */
	private $path;

	/**
	 * Init the object and set the log file path
	 */
	public function __construct($path)
	{
		$this->setPath($path);
	}

	/**
	 * Set the path for the current instance
	 *
	 * @param string $path File path
	 */
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * Get the current log file path
	 *
	 * @return string Path to log file
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * Write the message to the log
	 *
	 * @param mixed $message Message to write to the log (string or array)
	 */
	public function log($message)
	{
		if (!is_array($message)) {
			$message = array($message);
		}
		file_put_contents($this->getPath(), implode('|', $message)."\n", FILE_APPEND);
	}

	/**
	 * Write over the entire log file
	 *
	 * @param array $data Log file data (array of lines)
	 */
	public function write($data)
	{
		$lines = '';
		foreach($data as $d) {
			$d = trim($d);
			$lines .= (is_array($d)) ? implode('|', $data)."\n" : $d."\n";
		}
		file_put_contents($this->getPath(), $lines);
	}

	/**
	 * Get entire contents of the log
	 *
	 * @return array Log contents
	 */
	public function get()
	{
		$file = $this->getPath();
		return (is_file($file)) ? file($file) : array();
	}
}
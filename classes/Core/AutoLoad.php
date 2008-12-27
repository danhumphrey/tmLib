<?php
/**
  * AutoLoad class
  *
  * @package tmLib
  * @subpackage Core
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * AutoLoad is a static class that provides a method of auto-loading
 * your class files and avoiding includes
 *
 * @package tmLib
 * @subpackage Core
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @static
 * @version 1.1
 */
class AutoLoad {

	private static $filenameTemplates = array("@CLASS@.php", "@CLASS@.class.php");
	private static $classPaths = array();
	private static $cache = array();
	private static $cacheFile;
	private static $cacheUpdated = false;

	private static $className;

	/**
	 * Add a class path to the auto load system
	 *
	 * @param string $path
	 */
	static function addClassPath($path) {
		self::$classPaths[] = $path;
	}

	/**
	 * Add a file name template to the auto load system
	 *
	 * **EXAMPLE**
	 *
	 * Autoload::addFileNameTemplate('MyApp@CLASS@.php');
	 * @param string $template the template using @CLASS@ as the class name
	 */
	static function addFileNameTemplate($template) {
		self::$filenameTemplates[] = $template;
	}
	/**
	 * Sets the cache file and enables caching
	 *
	 * @param string $filePath the path to the class file
	 * @return bool true if valid file
	 */
	static function setCacheFile($filePath) {
		if($file = @fopen($filePath,"a")) {
			fclose($file);
			self::$cacheFile = $filePath;
			return true;
		}
	}
	/**
	 * loadClass attempts to autoload a class called className from the cache or the classPaths provided
	 *
	 * @param string $className name of the class
	 * @return boolean true if the class was loaded
	 */
	static function loadClass($className) {
		self::$className = $className;
		//try memory
		if(class_exists($className)) {
			return true;
		}
		//try cache
		if(isset(self::$cacheFile) && is_readable(self::$cacheFile)) {
			include(self::$cacheFile);
			self::$cache = is_array(@$tmLibAutoLoadCacheArray) ? $tmLibAutoLoadCacheArray : array();
			if(array_key_exists($className,self::$cache)) {
				require_once(self::$cache[$className]);
				return true;
			}
		}

		//try file system
		$matches = self::getFileNameMatches($className);

		foreach (self::$classPaths as $classPath) {
			if($file = self::findFile($matches, $classPath)){
				require_once($file);
				self::addToCache($className,$file);
				self::writeCache();
				return true;
			}
		}
		return false;
	}

	private static function addToCache($class, $path) {
		if(!array_key_exists($class, self::$cache)) {
			self::$cache[$class] = $path;
			self::$cacheUpdated = true;
		}
	}
	private static function writeCache() {
		if(isset(self::$cacheFile) && self::$cacheUpdated) {
			$header = '<?php' . "\n";
			$header .= '//Auto Generated Cache File (tmLib AutoLoad)' ."\n";
			$header .= '//To clear the cache, delete the array entries below:' ."\n";
			$items = '';
			foreach (self::$cache as $class => $path){
				$items .= '$tmLibAutoLoadCacheArray' ."['$class'] = '$path';" ."\n";
			}
			$footer = '//Do not delete below this line!' ."\n";
			$footer .= '?>' ."\n";
			if($handle = fopen(self::$cacheFile,"w+")) {
				fwrite($handle,$header);
				fwrite($handle,$items);
				fwrite($handle,$footer);
			}
		}
	}

	private static function getFileNameMatches($classname) {
		return str_replace("@CLASS@", $classname, self::$filenameTemplates);
	}

	private static function findFile($file) {
		// normalize
		if(!is_array($file)) {
			$file = array($file);
		}
		// recursion start dir
		if(func_num_args() > 1) {
			$startDir = func_get_arg(1);
		}

		$subDirectories = array();
		$dirIt = new DirectoryIterator($startDir);

		foreach($dirIt as $dirItem) {
			if($dirItem->isDot()) {
				continue;
			}

			if($dirItem->isDir()) {
				$subDirectories[] = $dirItem->getPathname();
				continue;
			}
			if(in_array($dirItem->getFilename(), $file)) {
				//matching name
				return $dirItem->getPathname();
			}
		}
		foreach($subDirectories as $directory) {
			$result = self::findFile($file, $directory);
			if($result) {
				return $result;
			}
		}
		return false;
	}
}
?>
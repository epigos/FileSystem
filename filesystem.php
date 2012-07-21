<?php

/**
 *FileSystem Object
 
 *  A singleton object which provides convenience methods for managing the file system in PHP 5. 
 * You can get the object's instance using the static {@link getInstance()} method.   
 *  
 * <b>Getting Started</b>
 *  <code>
 *  include "filesystem.php";
 *  $file = FileSystem::getInstance();
 *  $fileExt=$file ->getExtension("foobar.txt");
 *  echo $fileExt; // txt
 * 
 *  </code>
 *
 *  @package    file-system
 *  @author     Philip Adzanoukpe <info@philipcodings.com * @url http://philipcodings.com>
 *  @copyright  (c) 2012 - Philip Adzanoukpe
 *  @version    1.0
 *  @license 	Free to use and modify as you choose. Please give credits.
 */

 
 Class FileSystem {
	
    private static $instance;
    
    
  
/**
     *  Constructor
     *
     *  Private constructor as part of the singleton pattern implementation.
     */
    private function __construct() {}

 
 /**
     *  Get Instance
     *
     *  Gets the singleton instance for this object. This method should be called
     *  statically in order to use the filesystem object object:
     *
     *  <code>
     *  $file = FileSystem::getInstance();
     *  </code>
     *
     *  @return FileSystem
     */
    public static function getInstance()
    {
        if (!isset(self::$instance))
        {
            self::$instance = new FileSystem();
        }
        
        return self::$instance;
    }
    
             
        /**
	 * Get extension from a string
	 *
	 * @param string $filename
	 * @return string
	 */
	
	
	public function getExtension($filename)
                {
                
                $fileParts = explode(".",$filename);
		return end($fileParts);
                
		
	}
	
	/**
	 * Read file content
         * 
	 * <code>
         * Local:
         * $data=$file ->Read("foobar.txt");
         * echo $data;
         * 
         * Reomte:
         * $data=$file ->Read("http://www.google.com.gh/",TRUE);
         * echo $data;
         * 
         * </code>
         * 
	 * @access public
	 * @param string $filename file path
         * @param bool $remote specify if file is on a server
	 * @return string
         * 
	 */
	public function Read($filename,$remote=false){
            if (!$remote){
                if (file_exists($filename)){
                $handle = fopen($filename, "r");
		$content = fread($handle, filesize($filename));
                fclose($handle);
                return $content;
                }
                else {return "The specified filename does not exist";}
            }
            else{
                
                $content = file_get_contents($filename);
                return $content;
            }
        
        }
	/**
	 * Write content to file
         * 
	 * <code>
         * $data=$file ->Write("Foo bar","foobar.txt");
         *</code>
         * 
	 * @access public
	 * @param string $data
	 * @param string $filename
         * @param bool $append
	 * @return bool
	 */
	public function Write($data,$filename,$append=false){
            if (!$append){$mode="w";} else{$mode="a";}
		if($handle = fopen($filename,$mode)){
			fwrite($handle, $data); 
			fclose($handle); 
			return true;
		}
		return false;
	}
        
        
   /**
     * Creates a new directory. If the path to the directory does not
     * exist it will also be created
     * 
     * @param String $path 
     */
      public function createDirectory($path)
      {
          $this->mkdir($path);
      }
        
    private function mkdir($path) {
        $path = str_replace("\\", "/", $path);
        $path = explode("/", $path);

        $rebuild = '';
        foreach($path AS $p) {

            // Check for Windows drive letter
            if(strstr($p, ":") != false) { 
                $rebuild = $p;
                continue;
            }
            $rebuild .= "/$p";
            //echo "Checking: $rebuild\n";
            if(!is_dir($rebuild)) mkdir($rebuild);
        }
    }
    
/**
	 * Delete a file or directory
	 * 
	 * @access public
         * @param string $src 
	 * @return bool
	 */
	public function Delete($src){
		if(is_dir($src) && $src != ""){
			$result = $this->Listing();
			
			// Bring maps to back
			// This is need otherwise some maps
			// can't be deleted
			$sort_result = array();
			foreach($result as $item){
				if($item['type'] == "file"){
					array_unshift($sort_result, $item);
				}else{
					$sort_result[] = $item;
				}
			}

			// Start deleting
			while(file_exists($src)){
				if(is_array($sort_result)){
					foreach($sort_result as $item){
						if($item['type'] == "file"){
							@unlink($item['fullpath']);
						}else{
							@rmdir($item['fullpath']);
						}
					}
				}
				@rmdir($src);
			}
			return !file_exists($src);
		}else{
			@unlink($src);
			return !file_exists($src);
		}
	}
	
	/**
     * Recursively copy files from one directory to another
     *
     * @param String $src - Source of files being moved
     * @param String $dest - Destination of files being moved
     */
    function copy($src, $dest){

        // If source is not a directory stop processing
        if(!is_dir($src)) return false;

        // If the destination directory does not exist create it
        if(!is_dir($dest)) {
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }
        }

        // Open the source directory to read in files
        $i = new DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                copy($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->copy($f->getRealPath(), "$dest/$f");
            }
        }
    }
    
    /**
     * Moves files inside the $src folder to the $dest folder. If $dest does
     * not exist it will be created.
     * 
     * NOTE: Moves the files _inside_ the $src folder, not the $src folder itself
     * 
     * This function removes $src
     * 
     * @param String $src - File path to source folder
     * @param type $dest - File path to destination folder
     * @return false on failure 
     */
    function move($src, $dest){

        // If source is not a directory stop processing
        if(!is_dir($src)) {
            rename($src, $dest);
            return true;
        }

        // If the destination directory does not exist create it
        if(!is_dir($dest)) { 
            if(!mkdir($dest)) {
                // If the destination directory could not be created stop processing
                return false;
            }    
        }

        // Open the source directory to read in files
        $i = new DirectoryIterator($src);
        foreach($i as $f) {
            if($f->isFile()) {
                rename($f->getRealPath(), "$dest/" . $f->getFilename());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->move($f->getRealPath(), "$dest/$f");
                @unlink($f->getRealPath());
            }
        }
        @unlink($src);
    }
    
    /**
     * List the files in a given directory
     * <code>
     * $src = "c:\foobar";
     * $data = $file ->listing($dest);
     * print_r($data);
     * </code>
     * @param String $path
     * @return Array
     */
    public function listing($path) {
        $arr = array();
        if(is_dir($path)) { 
                // Open the source directory to read in files
            $i = new DirectoryIterator($path);
            foreach($i as $f) {
                if(!$f->isDot())
                    $arr[] = $f->getFilename();
            }
            return $arr;
        }
        return false;
    }
    /**
     * Removes all the content of a given directory ($path)
     * 
     * @param String $path 
     */
    public function rmdirContent($path) {
        // Open the source directory to read in files
        $i = new DirectoryIterator($path);
        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                rmdir($f->getRealPath());
            }
        }
        
    }
    /**
     * Remove the specified files or folders
     * 
     * @param String $path
     */
    public function remove($path) {
        if(is_dir($path)) {
            rmdir($path);
        } else {
            unlink($path);
        }
    }
    
    /**
     * Find the specified extension($ext) in the directory ($path)
     * <code>
     * $ext="txt";
     * $src = "c:\foobar";
     * $data = $file ->findByExtension($src, $ext);
     * print_r($data);
     * </code>
     * 
     * @access public
     * @param string $path - directory to serach
     * @param String $ext - the extension to look for
     * @return Array
     */
    
    public function findByExtension($path, $ext){
        $arr = array();
        $files = $this->listing($path);
        
        foreach ($files as $f) {
            $info = pathinfo($path . "/$f");
            if(isset($info['extension']) && $info['extension'] == $ext) 
                    $arr[] = $path . "/$f";
        }
        return $arr;
    }
    
 }

?>


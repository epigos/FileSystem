FileSystem
====

FileSystem  is a singleton PHP object which provides convenience methods for managing the file system in PHP. 

This PHP object contains eleven(11) methods:

	1."getExtension" - Returns the extention of the specified file.
	2."Read" - Reads entire file.
	3. "Write" - Write data to a file.
	4. "createDirectory" - create a folder in the specified path.
	5. "Delete" - Deletes a file or a folder.
	6. "Copy" - copies files from one directory to another.
	7. "move" - move files from one directory to another.
	8. "Listing" - list all the files in a given directory.
	9. "rmdirContent" - removes all the content in a given folder.
	10. "remove" - removes a specified file or folder.
	11. "findByExtension" - Find the specified extension in the directory.


Basic Example
-------------

You can get the object's instance using the static {@link getInstance()} method.   
 

    <?php
	
    include "filesystem.php";
	$file = FileSystem::getInstance();
	$fileExt=$file ->getExtension("foobar.txt");
	echo $fileExt; // txt

    ?>
	
	
 Read file content
        <?php
		
         Local file :
         $data=$file ->Read("foobar.txt");
         echo $data;
          
         Reomte file:
         $data=$file ->Read("http://www.google.com.gh/",TRUE);
         echo $data;
		 
         ?>
	 




License
-------

Copyright (C) 2012 by philipcodings.com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
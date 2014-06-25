helloworld
==========

FreePBX Hello World with BMO for 12+

Status
==========
This is not a complete or working example. Consider this a scratchpad as I work it out.  Final version will be availible from the FreePBX git once I have a base working example

Scope
==========

It is not possible to both show everything and make this module useful as a learning tool. I do not wish to drown people in so much code that you may as well just open any module and figure it out.  So what should this module demonstrate.

- [x] Basic Structure
- [x] Write to database
- [x] Read from database
- [ ] Write a conf file
- [ ] Read a conf file
- [ ] Display a page
- [ ] Display a populated form
- [ ] Handle a form submission
- [ ] Write dial plan

The UCP module will have it's own scope and read me file. It is not the primary focus of this example so it may or may not be included here. 

Structure
==========
##Folder tree

 * rawname
  * assets
	* js
	* css
  * views
  * UCP
   * assets
	* js
	* css
   * views


rawname - Should be the name of your module

assets - Contains your CSS and Java Script files in their respective folders.

views - Contains the html page views. These files should not contain any logic.

UCP - Optional adds a module for the user control pannel. Has the same directory structure. 

##Files
rawname/Rawname.class.php
This is your primary class and is essentially your module. All the magic happens here.
rawname/module.xml
This is your module definition and is the same as previous FreePBX versions.

## Rawname.class.php
The standard here is first letter capitalized.

## Required methods (even if you don't use them)
This is th minimum methods allowed. There are other methods but these are manditory.
 * public function install() {}
 * public function uninstall() {}
 * public function backup() {}
 * public function restore($backup) {}
 * public function doConfigPageInit($page) {}

### install 
replaces install.php
### uninstall 
clean up after your self
### backup 
FUTURE: generate an array that can be used to restore
### restore 
FUTURE: this will be given the array you generated prior which you should parse and make things the way they were.
### doConfigPageInit
Without this you will get an error. This will receive the page requested which you should act on accordingly.

## Functional notes
PHP tends to poop it self in some cases if there is a "?>" followed by a empty newline. Do not use the closing ?>
unless you are doing inline PHP or you may break something and have no idea why.


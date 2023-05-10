# helloworld

FreePBX Hello World with BMO for 13+ though a lot of this will probably work on 12+

# Status

This is a functioning module that manages notes. It installs and all that jazz on FreePBX 13. It takes advantage of
some 13 only stuff such as action buttons that won't show up in 12, and a dynamic grid that won't populate in 12.

# Scope

It is not possible to both show everything and make this module useful as a learning tool. I do not wish to drown people in so much code that you may as well just open any module and figure it out. So what should this module demonstrate.

-   [x] Basic Structure
-   [x] Write to database
-   [x] Read from database
-   [ ] Write a conf file
-   [ ] Read a conf file
-   [x] Display a page
-   [x] Display a populated form
-   [x] Handle a form submission
-   [x] Write dial plan

The UCP module will have it's own scope and read me file. It is not the primary focus of this example so it may or may not be included here.

TODO:

-   Hook in to pages.
-   Make this thing create/edit a conf file.
-   Comment and Clarify things.

# Structure

## Folder tree

-   rawname
-   assets
    -   js
    -   css
-   views
-   UCP
-   assets
-   js
-   css
-   views

rawname - Should be the name of your module

assets - Contains your CSS and Java Script files in their respective folders.

views - Contains the html page views. These files should generally not contain any logic.

UCP - Optional adds a module for the user control pannel. Has the same directory structure.

## Files
rawname/Rawname.class.php
This is your primary class and is essentially your module. All the magic happens here.
rawname/module.xml
This is your module definition and is the same as previous FreePBX versions.
rawname/rawname.page.php

## Rawname.class.php

The standard here is first letter capitalized.

## Required methods (even if you don't use them)

This is th minimum methods allowed. There are other methods but these are manditory.

-   public function install() {}
-   public function uninstall() {}
-   public function backup() {}
-   public function restore(\$backup) {}
-   public function doConfigPageInit(\$page) {}

### install method

replaces install.php

### uninstall method

clean up after your self

### backup method

FUTURE: generate an array that can be used to restore

### restore method

FUTURE: this will be given the array you generated prior which you should parse and make things the way they were.

### doConfigPageInit method

Without this you will get an error. This will process \$\_REQUEST

## Functional notes

PHP tends to poop it self in some cases if there is a "?>" followed by a empty newline. Do not use the closing ?>
unless you are doing inline PHP or you may break something and have no idea why.

# Resources

Uniformity Guidelines: http://wiki.freepbx.org/x/goDNAQ
Code Snippits: https://github.com/jfinstrom/FreePBX-gists

# Spam

If this code was useful to you please show your support by supporting my employer. Though this is not an official project it
is through my employer that I feed my family, pay my power bill, internet bill etc to allow me to create stuff.

https://clearlyip.com

## License

AGPLv3+

#Minimee+LESS

* Author: [John D Wells](http://johndwells.com)

## Version 1.x

### Requirements:

* PHP5
* ExpressionEngine 2.1 or later
* Minimee 2.x


## Description

Run [lessphp](http://leafo.net/lessphp) on your CSS files, just prior to Minimee running them through Minify.

This extension is currently quite basic, and barely tested so please use with caution. Its immediate purpose is to demonstrate the extension possibilities with the upcoming release of Minimee 2.x.

_Note: Even if you have turned Minimee's CSS minification off, LESS will still be run._


## Usage

Simply add less="yes" to your minimee tags, like so:

	{exp:minimee:css
		less="yes"
		lessImportDir="/path/to/less/imports|/path/to/less"}
	    <link href="/less/styles.less" type="text/css" rel="stylesheet" media="screen" />
	{/exp:minimee:css}
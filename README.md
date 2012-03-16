# Minimee+LESS

Adds [LESS](http://lesscss.org/) processing to [Minimee 2+](https://github.com/johndwells/Minimee/tree/version2).


# Version 1.x (current BETA)

_Beta means be wary of using in production environments. Beta also means your feedback is hugely appreciated._


## Requirements:

* PHP5
* [ExpressionEngine 2](http://www.expressionengine.com)
* [Minimee 2+](https://github.com/johndwells/Minimee/tree/version2) (currently beta)


# Description

Minimee+LESS runs [lessphp](http://leafo.net/lessphp) on your CSS files, just prior to Minimee running them through Minify.

This extension is currently quite basic, and barely tested so please use with caution. Its immediate purpose is to demonstrate the extension possibilities with the upcoming release of Minimee 2.x.

_Note: Even if you have turned Minimee's CSS minification off, LESS will still be run._


# Installation

Coming soon - but basically, install like any other add-on, and activate the extension.


# Configuration

Coming soon - look at the extension for more.


# Usage

There is nothing you will need to do differently to have Minimee+LESS run. Simply use Minimee as normal (you can even mix .less and .css files):

	{exp:minimee:css
	    <link href="/less/reset.css" type="text/css" rel="stylesheet" media="screen" />
	    <link href="/less/styles.less" type="text/css" rel="stylesheet" media="screen" />
	{/exp:minimee:css}



# Changelog

* 1.0.0 - Initial release


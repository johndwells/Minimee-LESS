#LESS for Minimee

* Author: [John D Wells](http://johndwells.com)

## Version 1.x

### Requirements:

* PHP5
* ExpressionEngine 2.1 or later
* Minimee 2.x


## Description

Run [lessphp](http://leafo.net/lessphp) on your CSS files, just prior to Minimee running them through Minify.


## Usage

Simply add less="yes" to your minimee tags, like so:

	{exp:minimee:css less="yes"}
	    <link href="/less/styles.less" type="text/css" rel="stylesheet" media="screen" />
	{/exp:minimee:css}
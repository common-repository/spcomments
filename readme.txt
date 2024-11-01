=== SP Comments Shortcodes Kit ===
Author: Fabio Ottaviani for Pro Zeta Gamma http://www.prozac2000.com
Contributors: prozetagamma
Tags: comments, column, shortcode
Requires at least: 3.7
Tested up to: 4.8
Stable tag: 1.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Divide comments in arguments and allowing show where you want by shortcode

== Description ==

To insert Form & Button:

* [spcomments_button

 text =			(optional) "button title"								default: "Submit"
 args = 		(optional) "argument1,argument2,argument3..."			default: "Argument1,Argument2,Argument3"
 formtitle =	(optional) "title of form"								default: "Leave a Reply"
 popupstyle =	(optional) "spoiler|modal"								default: "spoiler"
 id =			(optional) an valid numeric id							default: random number id or a name without spaces
 placeholder =	(optional) "form textarea placeholder text"				default: "Your comment here..."
 cflabel =		(optional) "label of textarea comment text"				default: "Comment"
 
 ]
 
To insert a Secondary Button:

* [spcomments_button

 text =			(optional) "button title"								default: "Submit"
 formid =		(required) the id of existing form to open
 arg =			(optional) the name of arg to select					default: none
 hideargs = 	(optional) "yes|no"										default: "no"
 
 ]
 

To show a comment list:

* [spcomments

 arg =					(required) "argument1"
 nocommentstext =		(optional) "Text if none comment in argument"	default "No comments yet on this topic"
 
 ]


[spcomments arg="argument2"]
[spcomments arg="argument3"]


To insert a comment textarea (with argument preselected)

* [spcomments_textarea

	id =				(optional) a valid numeric id					default: random number id or a name without spaces
	arg =				(required) the name of arg to select			default: none
	formid =			(optional) an id to use for javascript 
	formtitle =			(optional) "title of form"						default: "Leave a Reply"
	cflabel =			(optional) "label of textarea comment text"		default: "Comment"
	placeholder =		(optional) "form textarea placeholder text"		default: "Your comment here..."
	
  ]

if you need a live demo [click here](https://www.prozac2000.com/wp-lab/index.php/spcomments-shortcodes-kit/)

== Screenshots ==

1. Two comment buttons: the first with 2 arguments, and second with 3 cities

== Faq ==

= How many topics can I put into a button? =
There is no limit, but do not overdo it

= Can I use it in all the pages I want? =
Yes

= How many pop-up types are available? =
Actually two. Spoiler & Modal

= Can i view a live demo? =
if you need a live demo [click here](https://www.prozac2000.com/wp-lab/index.php/spcomments-shortcodes-kit/)

= I've a question about this plugin =
For more questions please email me on debug@chicercatrova2000.it

= Can i insert a secondary button that open a form with a selected option? =
Yes.
e.g.: [spcomments_button formid="products" arg="milk" text="choose milk" hideargs="yes"]
Display a button with text "choose milk" that select "milk" option in comment form width id "products" and open it. The options list will be hidden. 

== Installation ==

Upload the Sp Comments plugin to your blog, Activate it. Good work!

== Changelog ==

= 1.8 =
*Release Date - 21 May 2017*

* add formid attribute in spcomments_button shortcode for secondary buttons
* add arg attribute in spcomments_button shortcode
* add hideargs attribute in spcomments_button shortcode

= 1.4 =
*Release Date - 15 April 2017*

* add nocommenttext attribute in spcomments

= 1.3 =
*Release Date - 13 April 2017*

* add placeholder attribute
* add cflabel attribute

= 1.2 =
*Release Date - 12 April 2017*

* add id attribute (optional)
* add multiple button in the same pages

= 1.1 =
*Release Date - 05 April 2017*

* add formtitle attribute.
* add popupstyle attribute.

= 1.0 =
*Release Date - 03 April 2017*

* First Shortcode.
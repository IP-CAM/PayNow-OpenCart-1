Sage Pay Now OpenCart Module OpenCart
=====================================

Revision 1.0.1

Introduction
------------
Sage Pay South Africa's Pay Now third party gateway integration for OpenCart.

Installation Instructions
-------------------------
Download the files from GitHub:
* https://github.com/SagePay/PayNow-OpenCart/archive/master.zip

Copy all the files to your OpenCart /admin and /catalog folders.

Configuration
-------------

Prerequisites:

You will need:
* Sage Pay Now login credentials
* Sage Pay Now Service key
* OpenCart admin login credentials

Sage Pay Now Gateway Server Configuration Steps:

1. Log into your Sage Pay Now Gateway Server configuration page:
	https://merchant.sagepay.co.za/SiteLogin.aspx
2. Go to Account / Profile
3. Click Sage Connect
4. Click Pay Now
5. Make a note of your Service key

Sage Pay Now Callback

6. Choose both the following URLs for your Accept and Decline URLs:
	http://opencart_installation/index.php

OpenCart Steps:

1. Log into OpenCart as admin
2. Click on Extensions / Payments
3. Scroll to Sage Pay Now and click Install
4. Click 'Edit' next to Sage Pay Now
5. Type in your Sage Pay Now Service Key
6. Match payment statusses, i.e. choose Complete, Failed, and Cancelled
7. Click 'Save'

Revision History
----------------

* 7 May 2014/1.0.1
** Variable mismatch in TPL file caused Server Error at Sage Pay Now
** Improved documentation

Tested with OpenCart version 1.5.6.1

Demo Site
---------
Here is a demo site if you want to see OpenCart and the Sage Pay Now gateway in action:
http://opencart.gatewaymodules.com

Issues & Feature Requests
-------------------------
Please log any issues or feature requests on GitHub or contact Sage Pay South Africa

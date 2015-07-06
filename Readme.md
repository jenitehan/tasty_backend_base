#Tasty Backend Base Module Readme

The Tasty Backend base module will create admin views for each of your content types, arrange fields on content types in vertical tabs, and provide the 'content admin' and 'user admin' roles.

It will also assign the navigation menu as the admin menu for users with the content admin or user admin roles

All the admin views for content types are only viewable if the user has the "*edit any [content type]*" permission

And there's also a permission to access tasty backend admin pages. So if roles outside of the "content admin" role need to access those pages, they'll need that permission.

The Tasty Backend modules do **not** require you to use the Tasty Backend Install Profile. You can try it out on a new install someday.

###Installation
Download the zip and place the expanded folder in your /sites/default/all/modules folder.

Go to admin/modules and make sure you have all the prerequisite modules installed, and then activate the Tasty Backend Base Module.

####If you are not using the Tasty Backend Install Profile
**(ie. adding it to an existing install)**

The context admin module is best with a few patches.  The install profile patches it, but without that, use these:

* https://drupal.org/files/undefined_index-1760610-3.patch
* https://drupal.org/files/issues/context_admin-reset_terms_alphabetical_redirect-2276567-1.patch

Here's the details on those:
* https://www.drupal.org/node/1760610

These patches apply fine to latest dev of context admin
###About Tasty Backend
Tasty Backend Modules and Base Install Profiles were created by @jenitehan using the usability improvements spoken about in her DrupalCon talks 'Building a Tasty Backend'

You can view one of these at [Building a Tasty Backend](https://amsterdam2014.drupal.org/session/building-tasty-backend.html) from DrupalCon Amsterdam '14

A Tasty Backend Demo is available here: http://tastybackend.deliciouscreative.com

[Jeni Tehan](https://github.com/jenitehan) . Delicious Creative . Brighton, UK .
http://www.deliciouscreative.com



# ProcessWire Page Edit Field Permission

## Enables you to limit edit access (by role) to any field in the page editor.

### To Install

Place all of the files for this module in /site/modules/PageEditFieldPermission/

### How it works

This module hooks in to modify the behavior of Page::editable, which is used throughout
ProcessWire, but most notably by Page Edit. This module looks for permissions in the 
system that follow the name format of **page-edit-[field]** where [field] is the name of 
an existing field in the system. When it finds such a permission during a Page::editable
call, it checks to see if the roles from the current user have this permission assigned.
If they do not, then permission to the relevant field is refused, thereby preventing 
edit access to the field.

### How to use it

Once the module is installed, you get a set of checkboxes on the module configuration 
screen. Check the boxes next to each field that you would like it to create permissions
for. (Alternatively, you can create the permissions yourself, so this is just a shortcut).
*You should only create permissions for fields that you intend to limit access to.*

Once your new page-edit-[field] permissions are created, any non-superuser roles that 
previously had access to edit those fields will no longer have access to edit them. To 
give them access, you must edit a role and check the box for the relevant permission. 

------------

November 2, 2012 by Ryan Cramer


<?php

/**
 * Provides the implementation for the PageEditFieldPermission::getModuleConfigInputfields method
 *
 */

function PageEditFieldPermissionConfig(array $data) {

	$inputfields = new InputfieldWrapper();

	$f = wire('modules')->get('InputfieldMarkup');
	$f->label = 'How to use this module';
	$f->attr('name', '_instructions');
	$config = wire('config');
	$f->value = <<< _OUT

	<p>
	To use, create a <a href='{$config->urls->admin}access/permissions/'>new permission</a> 
	and name it <b>page-edit-[field]</b>, replacing [field] with the name of the field you 
	want to limit access to. <em>Better yet, use the tool below to create them for you and save some time.</em>
	</p>

	<p> 
	Once your page-edit-[field] permission(s) exist, <a href='{$config->urls->admin}access/roles/'>add them to any roles</a> 
	that you want to have edit access to the field. Roles that have edit access to a page, but do not have the 
	required page-edit-[field] permission will not be able to see or modify the [field] in the page editor. 
	</p>

	<p>Note that none of this applies to users with the superuser role, as they always have access to edit everything.</p>

_OUT;

	$createPermissions = wire('input')->post->_create_permissions;
	if($createPermissions) foreach($createPermissions as $name) {
		$name = wire('sanitizer')->pageName($name);
		$permission = wire('permissions')->add("page-edit-$name");
		$permission->title = "Access to edit the '$name' field"; 
		$permission->save();
		wire('modules')->message("Added permission: $permission->name");
	}

	$inputfields->add($f);

	$f = wire('modules')->get('InputfieldCheckboxes');
	$f->attr('name', '_create_permissions'); 
	$f->label = 'Handy tool to create permissions for you';
	$f->optionColumns = 3; 
	$f->description = 'Check the box next to each field name you would like this tool to create a permission for you. This is the same thing as going to the Permissions page and creating them yourself, so this is here primarily as a time saver.';
	$fields = array('name', 'parent', 'template', 'status'); 
	$notes = '';
	foreach(wire('fields') as $field) $fields[] = $field->name; 
	foreach($fields as $name) {
		if($name == 'pass') continue; 
		if(wire('permissions')->get("page-edit-$name")->id) {
			$notes .= "$name, ";
			continue; 
		}
		$f->addOption($name);
	}
	if(!$notes) $notes = "[none yet]";
	$f->notes = 
		"Fields that already have permissions: " . rtrim($notes, ", ") . ". " . 
		"Non-superuser roles that have page-edit access will no longer be able to see/edit these fields unless the appropriate permission is assigned to that role. ";
	$inputfields->add($f);

	return $inputfields;

}

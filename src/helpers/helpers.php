<?php

if(class_exists('kamruljpi\Role\Http\Model\UserRoleMenu') && method_exists('kamruljpi\Role\Http\Model\UserRoleMenu', 'generateMenu') && !function_exists('generateMenu')){

	function generateMenu() {

		$menus = kamruljpi\Role\Http\Model\UserRoleMenu::generateMenu();

		return $menus;

	}
}

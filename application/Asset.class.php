<?php
/*
 * Asset - tracks Minify groups so each resource
 * only loads the libraries it needs
 *
 */
class Asset
{
	private $cssGroups = array();
	private $jsGroups  = array();
	
	public function SetJSGroups($groups)
	{
		$this->jsGroups = $groups;
	}
	
	public function AddJS($groupName)
	{
		$this->jsGroups[] = $groupName;
	}
	
	public function GetJSGroups()
	{
		return implode(',', $this->jsGroups);
	}
	
	public function SetCSSGroups($groups)
	{
		$this->cssGroups = $groups;
	}
	
	public function AddCSS($groupName)
	{
		$this->cssGroups[] = $groupName;
	}
	
	public function GetCSSGroups()
	{
		return implode(',', $this->cssGroups);
	}
}
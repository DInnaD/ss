<?php

namespace App\Http\Helpers;


use App\User;

abstract class Draft
{

    const IS_DRAFT = 0;
	const IS_PUBLIC = 1;


    public function setDraft()
    {
    	$draft = self::IS_DRAFT;
    	
    }

    public function setPublic()
    {
    	$public = self::IS_PUBLIC;
    	
    }

    public function toggleStatus($value)
    {
    	if($value == null)
    	{
    		return $draft->setDraft();
    	}

    	return $public->setPublic();
    }
   
}

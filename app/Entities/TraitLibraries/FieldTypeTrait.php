<?php 

namespace App\Entities\TraitLibraries;

/**
 * available function who hath Type trait
 *
 * @author cmooy
 */
trait FieldTypeTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function FieldTypeTraitConstructor()
	{
		//
	}

	/**
	 * scope to get condition where Type
	 *
	 * @param string or array of entity' Type
	 **/
	public function scopeType($query, $variable)
	{
		if(is_array($variable))
		{
			$query = $query->whereIn($query->getModel()->table.'.type', $variable);

			return $query;
		}

		return 	$query->where($query->getModel()->table.'.type', $variable);
	}
}
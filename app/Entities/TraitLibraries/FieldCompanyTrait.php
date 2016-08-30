<?php 

namespace App\Entities\TraitLibraries;

/**
 * available function who hath Company trait
 *
 * @author cmooy
 */
trait FieldCompanyTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function FieldCompanyTraitConstructor()
	{
		//
	}

	/**
	 * scope to get condition where Company
	 *
	 * @param string or array of entity' Company
	 **/
	public function scopeCompanyID($query, $variable)
	{
		if(is_array($variable))
		{
			$query = $query->whereIn($query->getModel()->table.'.company_id', $variable);

			return $query;
		}

		return 	$query->where($query->getModel()->table.'.company_id', $variable);
	}

	/**
	 * scope to get condition where not Company
	 *
	 * @param string or array of entity' Company
	 **/
	public function scopeCompanyNotID($query, $variable)
	{
		if(is_array($variable))
		{
			$query = $query->whereNotIn($query->getModel()->table.'.company_id', $variable);

			return $query;
		}

		return 	$query->where($query->getModel()->table.'.company_id', '<>', $variable);
	}
}
<?php 

namespace App\Entities\TraitRelations;

/**
 * Trait for Entities belongs to Account.
 *
 * @author cmooy
 */
trait BelongsToParentAccountTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function BelongsToParentAccountTraitConstructor()
	{
		//
	}
	
	/**
	 * call belongsto relationship with ParentAccount
	 *
	 **/
	public function ParentAccount()
	{
		return $this->belongsTo('App\Entities\Account');
	}
	
	/**
	 * check if model has ParentAccount
	 *
	 **/
	public function scopeHasParentAccount($query, $variable)
	{
		return $query->whereHas('parentaccount', function($q)use($variable){$q;});
	}

	/**
	 * check if model has ParentAccount in certain id
	 *
	 * @var singular id
	 **/
	public function scopeParentAccountID($query, $variable)
	{
		return $query->where('parent_account_id', $variable);
	}
}
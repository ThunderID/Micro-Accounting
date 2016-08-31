<?php 

namespace App\Entities\TraitRelations;

/**
 * Trait for Entities belongs to Account.
 *
 * @author cmooy
 */
trait BelongsToAccountTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function BelongsToAccountTraitConstructor()
	{
		//
	}
	
	/**
	 * call belongsto relationship with Account
	 *
	 **/
	public function Account()
	{
		return $this->belongsTo('App\Entities\Account');
	}
	
	/**
	 * check if model has Account
	 *
	 **/
	public function scopeHasAccount($query, $variable)
	{
		return $query->whereHas('account', function($q)use($variable){$q;});
	}

	/**
	 * check if model has Account in certain id
	 *
	 * @var singular id
	 **/
	public function scopeAccountID($query, $variable)
	{
		return $query->where('account_id', $variable);
	}

	/**
	 * check if model has Account
	 *
	 **/
	public function scopeAccountType($query, $variable)
	{
		return $query->whereHas('account', function($q)use($variable){$q->type($variable);});
	}
}
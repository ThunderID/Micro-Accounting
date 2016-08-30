<?php 

namespace App\Entities\TraitRelations;

/**
 * Trait for Entities belongs to Transaction.
 *
 * @author cmooy
 */
trait BelongsToTransactionTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function BelongsToTransactionTraitConstructor()
	{
		//
	}
	
	/**
	 * call belongsto relationship with Transaction
	 *
	 **/
	public function Transaction()
	{
		return $this->belongsTo('App\Entities\Transaction');
	}
	
	/**
	 * check if model has Transaction
	 *
	 **/
	public function scopeHasTransaction($query, $variable)
	{
		return $query->whereHas('transaction', function($q)use($variable){$q;});
	}

	/**
	 * check if model has Transaction in certain id
	 *
	 * @var singular id
	 **/
	public function scopeTransactionID($query, $variable)
	{
		return $query->where('transaction_id', $variable);
	}
}
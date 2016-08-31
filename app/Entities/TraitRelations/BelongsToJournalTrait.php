<?php 

namespace App\Entities\TraitRelations;

/**
 * Trait for Entities belongs to Journal.
 *
 * @author cmooy
 */
trait BelongsToJournalTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function BelongsToJournalTraitConstructor()
	{
		//
	}
	
	/**
	 * call belongsto relationship with Journal
	 *
	 **/
	public function Journal()
	{
		return $this->belongsTo('App\Entities\Journal');
	}
	
	/**
	 * check if model has Journal
	 *
	 **/
	public function scopeHasJournal($query, $variable)
	{
		return $query->whereHas('journal', function($q)use($variable){$q;});
	}

	/**
	 * check if model has Journal in certain id
	 *
	 * @var singular id
	 **/
	public function scopeJournalID($query, $variable)
	{
		return $query->where('journal_id', $variable);
	}

	/**
	 * check if model has Journal
	 *
	 **/
	public function scopeJournalType($query, $variable)
	{
		return $query->whereHas('journal', function($q)use($variable){$q->type($variable);});
	}
}
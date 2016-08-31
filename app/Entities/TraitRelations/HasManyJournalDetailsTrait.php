<?php 

namespace App\Entities\TraitRelations;

/**
 * Trait for models has many JournalLogs.
 *
 * @author cmooy
 */
trait HasManyJournalDetailsTrait 
{

	/**
	 * boot
	 *
	 * @return void
	 **/
	function HasManyJournalDetailsTraitConstructor()
	{
		//
	}

	/**
	 * call has many relationship
	 *
	 **/
	public function Details()
	{
		return $this->hasMany('App\Entities\JournalDetail', 'journal_id');
	}

	/**
	 * check if model has Journal details
	 *
	 **/
	public function scopeHasDetails($query, $variable)
	{
		return $query->whereHas('details', function($q)use($variable){$q;});
	}
	
	/**
	 * check if model has Journal details in certain id
	 *
	 * @var array or singular id
	 **/
	public function scopeDetailID($query, $variable)
	{
		return $query->whereHas('details', function($q)use($variable){$q->id($variable);});
	}
}
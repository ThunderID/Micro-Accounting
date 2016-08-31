<?php

namespace App\Entities;

use App\Entities\TraitRelations\BelongsToJournalTrait;
use App\Entities\TraitRelations\BelongsToAccountTrait;

use App\CrossServices\ClosedDoorModelObserver;

class JournalDetail extends BaseModel
{
	/**
	 * Relationship Traits
	 *
	 */
	use BelongsToJournalTrait;
	use BelongsToAccountTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table				= 'journal_details';
	
	/**
	 * Date will be returned as carbon
	 *
	 * @var array
	 */
	protected $dates				=	['created_at', 'updated_at', 'deleted_at'];

	/**
	 * The appends attributes from mutator and accessor
	 *
	 * @var array
	 */
	protected $appends				=	[];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden 				= [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $fillable				=	[
											'journal_id'					,
											'account_id'					,
											'description'					,
											'debit'							,
											'credit'						,
										];

	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'journal_id'					=> 'exists:journals,id',
											'account_id'					=> 'exists:accounts,id',
											'debit'							=> 'numeric',
											'credit'						=> 'numeric',
										];
	

	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	public static function boot() 
	{
        parent::boot();
     
        JournalDetail::observe(new ClosedDoorModelObserver());
    }
}

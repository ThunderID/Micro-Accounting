<?php

namespace App\Entities;

use App\Entities\TraitRelations\BelongsToTransactionTrait;
use App\Entities\TraitRelations\HasManyJournalDetailsTrait;

use App\Entities\TraitLibraries\FieldTypeTrait;
use App\Entities\TraitLibraries\FieldCompanyTrait;

class Journal extends BaseModel
{
	/**
	 * Relationship Traits
	 *
	 */
	use BelongsToTransactionTrait;
	use HasManyJournalDetailsTrait;

	/**
	 * Libraries Traits for scopes
	 *
	 */
	use FieldTypeTrait;
	use FieldCompanyTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table				= 'journals';
	
	/**
	 * Date will be returned as carbon
	 *
	 * @var array
	 */
	protected $dates				=	['created_at', 'updated_at', 'deleted_at', 'transact_at'];

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
											'company_id'					,
											'transaction_id'				,
											'transact_at'					,
											'type'							,
											'currency'						,
											'notes'							,
										];

	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'transaction_id'				=> 'exists:transactions,id',
											'transact_at'					=> 'required|date_format:"Y-m-d H:i:s"',
											'type'							=> 'required|in:cash,accrual',
											'currency'						=> 'max:255',
										];
	

	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	public static function boot() 
	{
        parent::boot();
    }
}

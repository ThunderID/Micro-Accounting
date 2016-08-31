<?php

namespace App\Entities;

use App\Entities\TraitRelations\BelongsToAccountTrait;
use App\Entities\TraitRelations\BelongsToTransactionTrait;
use App\Entities\TraitRelations\BelongsToParentAccountTrait;

use App\Entities\TraitLibraries\FieldCompanyTrait;

class Journal extends BaseModel
{
	/**
	 * Relationship Traits
	 *
	 */
	use BelongsToTransactionTrait;
	use BelongsToAccountTrait;
	use BelongsToParentAccountTrait;

	/**
	 * Libraries Traits for scopes
	 *
	 */
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
											'company_id'					,
											'transaction_id'				,
											'parent_account_id'				,
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
											'transaction_id'				=> 'exists:transactions,id',
											'description'					=> 'required',
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
    }
}

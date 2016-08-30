<?php

namespace App\Entities;

use App\Entities\TraitRelations\BelongsToTransactionTrait;

use App\CrossServices\ClosedDoorModelObserver;

class TransactionDetail extends BaseModel
{
	/**
	 * Relationship Traits
	 *
	 */
	use BelongsToTransactionTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table				= 'transaction_details';
	
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
											'transaction_id'				,
											'description'					,
											'quantity'						,
											'price'							,
											'discount'						,
										];

	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'transaction_id'				=> 'exists:transactions,id',
											'description'					=> 'required',
											'quantity'						=> 'numeric',
											'price'							=> 'numeric',
											'discount'						=> 'numeric',
										];
	

	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	public static function boot() 
	{
        parent::boot();
     
        TransactionDetail::observe(new ClosedDoorModelObserver());
    }

	/**
	 * scope to get condition where code
	 *
	 * @param string or array of entity' code
	 **/
	public function scopeDescription($query, $variable)
	{
		if(is_array($variable))
		{
			$query = $query->whereIn($query->getModel()->table.'.description', $variable);

			return $query;
		}

		return 	$query->where($query->getModel()->table.'.description', $variable);
	}
}

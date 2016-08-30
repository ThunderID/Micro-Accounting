<?php

namespace App\Entities;

use App\Entities\TraitRelations\HasManyTransactionDetailsTrait;

use App\Entities\Traits\HasAmountTrait;

use App\Entities\Traits\GetAllTrait;

use App\CrossServices\ClosedDoorModelObserver;

use App\Entities\TraitLibraries\FieldTypeTrait;
use App\Entities\TraitLibraries\FieldCompanyTrait;

/**
 * Used for receipt, cash note, cheque and many
 * 
 * @author cmooy
 */
class Transaction extends BaseModel
{
	/**
	 * Libraries Traits for scopes
	 *
	 */
	use FieldCompanyTrait;
	use FieldTypeTrait;

	/**
	 * Traits To Calculated Relations
	 */
	use HasAmountTrait;
	
	/**
	 * Traits To Condition within itself
	 */
	use GetAllTrait;

	/**
	 * Relationship Traits
	 *
	 */
	use HasManyTransactionDetailsTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table				= 'transactions';

	/**
	 * Date will be returned as carbon
	 *
	 * @var array
	 */
	protected $dates				=	['created_at', 'updated_at', 'deleted_at', 'issued_at', 'due_at'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $fillable				=	[
											'issued_by'						,
											'company_id'					,
											'assigned_to'					,
											
											'type'							,
											'doc_number'					,
											'ref_number'					,
											
											'issuer_information'			,
											'assignee_information'			,
											'company_information'			,

											'issued_at'						,
											'transact_at'					,
											'due_at'						,
										];
	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'issued_by'						=> 'numeric',
											'company_id'					=> 'numeric',
											'assigned_to'					=> 'numeric',
											
											'type'							=> 'in:receipt,cash_note,cheque,invoice,credit_memo,debit_memo,memorial,giro',
											'doc_number'					=> 'max:255',
											'ref_number'					=> 'max:255',
											
											'issued_at'						=> 'date_format:"Y-m-d H:i:s"',
											'transact_at'					=> 'date_format:"Y-m-d H:i:s"',
											'due_at'						=> 'date_format:"Y-m-d H:i:s"',
										];
	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
	
	/**
	 * boot
	 * observing model
	 *
	 */	
	public static function boot() 
	{
		parent::boot();
		
        Transaction::observe(new ClosedDoorModelObserver());
	}

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope to get condition where doc_number
	 *
	 * @param string or array of entity' doc_number
	 **/
	public function scopeDocNumber($query, $variable)
	{
		if(is_array($variable))
		{
			$query = $query->whereIn($query->getModel()->table.'.doc_number', $variable);

			return $query;
		}

		return 	$query->where($query->getModel()->table.'.doc_number', $variable);
	}
}

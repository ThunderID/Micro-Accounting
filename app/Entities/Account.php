<?php

namespace App\Entities;

use App\CrossServices\ClosedDoorModelObserver;

use App\Entities\TraitLibraries\FieldCompanyTrait;
use App\Entities\TraitLibraries\FieldTypeTrait;
use App\Entities\TraitLibraries\SelectReportTrait;

class Account extends BaseModel
{
	/**
	 * Libraries Traits for scopes
	 *
	 */
	use FieldCompanyTrait;
	use FieldTypeTrait;
	use SelectReportTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table				= 'accounts';
	
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
											'name'							,
											'code'							,
											'type'							,
										];

	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'company_id'					=> 'numeric',
											'name'							=> 'max:255',
											'code'							=> 'max:255',
											'type'							=> 'in:asset,liability,equity,income,expense',
										];
	

	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	public static function boot() 
	{
        parent::boot();

        Account::observe(new ClosedDoorModelObserver());
    }

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope to get condition where name
	 *
	 * @param string or array of entity' name
	 **/
	public function scopeName($query, $variable)
	{
		if(is_array($variable))
		{
			foreach ($variable as $key => $value) 
			{
				$query = $query->where($query->getModel()->table.'.name', 'like', '%'.$value.'%');
			}

			return $query;
		}
		return 	$query->where($query->getModel()->table.'.name', 'like', '%'.$variable.'%');
	}

	/**
	 * scope to get condition where code
	 *
	 * @param string or array of entity' code
	 **/
	public function scopeCode($query, $variable)
	{
		if(is_array($variable))
		{
			$query = $query->whereIn($query->getModel()->table.'.code', $variable);

			return $query;
		}

		return 	$query->where($query->getModel()->table.'.code', $variable);
	}
}

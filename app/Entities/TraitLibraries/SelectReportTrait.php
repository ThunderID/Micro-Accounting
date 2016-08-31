<?php 

namespace App\Entities\TraitLibraries;

/**
 * available function who hath report trait
 *
 * @author cmooy
 */
trait SelectReportTrait 
{
	/**
	 * boot
	 *
	 * @return void
	 **/
	function SelectReportTraitConstructor()
	{
		//
	}

	/**
	 * scope to get condition where report
	 *
	 * @param string or array of entity' report
	 **/
	public function scopeSelectLedger($query, $period, $mode)
	{
		return $query->selectraw('accounts.*')
					 ->selectraw('sum(IFNULL((SELECT sum(debit - credit) as amount FROM journal_details join journals on journal_details.journal_id = journals.id WHERE journal_details.account_id = accounts.id AND journal_details.deleted_at is null AND journals.type = "'.$mode.'" AND journals.transact_at <= "'.$period.'" AND journals.deleted_at is null),0)
					) as amount')
					 ->groupby('accounts.id')
					 ->orderby('accounts.type');
	}
}
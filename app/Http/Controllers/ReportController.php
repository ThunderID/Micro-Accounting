<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;
use Carbon\Carbon;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Entities\Account;

/**
 * Report resource representation.
 *
 * @Resource("Accounts", uri="/Reports")
 */
class ReportController extends Controller
{
	public function __construct(Request $request)
	{
		$this->request 				= $request;
	}

	/**
	 * Report general ledger
	 *
	 * Get a JSON representation of all the stored journal.
	 *
	 * @Get("/reports/general/ledger")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"type":"","search":[{"name":string, "companyid":"integer","code":"string","type":"asset|liability|equity|income|expense"}],"sort":[{"newest":"asc","company":"desc","type":"desc", "code":"asc"}], "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":[{"id":null,"company_id":"integer","name":"string","code":"string","type":"string"}],"count":"integer"} })
	 * })
	 */
	public function generalledger($mode = 'cash')
	{
		$periode					= Carbon::parse(Input::get('ondate'))->endOfDay()->format('Y-m-d H:i:s');

		//1. Assets
		$assets 					= Account::type('asset')->companyid(Input::get('company_id'))->selectledger($periode, $mode)->get();
		//2. liabilities
		$liabilities 				= Account::type('liability')->companyid(Input::get('company_id'))->selectledger($periode, $mode)->get();
		//3. equities
		$equities 					= Account::type('equity')->companyid(Input::get('company_id'))->selectledger($periode, $mode)->get();
		//4. incomes
		$incomes 					= Account::type('income')->companyid(Input::get('company_id'))->selectledger($periode, $mode)->get();
		//5. expenses
		$expenses 					= Account::type('expense')->companyid(Input::get('company_id'))->selectledger($periode, $mode)->get();

		$data['assets']				= $assets->toArray();
		$data['liabilities']		= $liabilities->toArray();
		$data['equities']			= $equities->toArray();
		$data['incomes']			= $incomes->toArray();
		$data['expenses']			= $expenses->toArray();
		
		return response()->json( JSend::success([$data])->asArray())
				->setCallback($this->request->input('callback'));
	}
}
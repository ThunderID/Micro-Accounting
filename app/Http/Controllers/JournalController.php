<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Entities\Journal;
use App\Services\JournalStore;
use App\Services\JournalDelete;

/**
 * Journal resource representation.
 *
 * @Resource("Journals", uri="/Journals")
 */
class JournalController extends Controller
{
	public function __construct(Request $request, JournalStore $store, JournalDelete $delete)
	{
		$this->request 				= $request;
		$this->store				= $store;
		$this->delete				= $delete;
	}

	/**
	 * Show all Journals
	 *
	 * Get a JSON representation of all the stored Journals.
	 *
	 * @Get("/Journals")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"type":"","search":[{"id":"integer", "transactionid":"integer","parentaccountid":"integer","accountid":"integer"}],"sort":[{"newest":"asc","transaction":"desc","debit":"desc", "credit":"asc"}], "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":[{"id":null,"company_id":"integer","transaction_id":"integer","transact_at":"datetime","type":"cash|accrual", "currency":"string","notes":"text","details":{"id":"integer","journal_id":"integer","account_id":"integer","debit":"integer","credit":"integer","account":{"company_id":"integer","name":"string","type":"string","code":"string"}},"transaction":{"id":"integer","amount":"integer","issued_by":"integer","company_id":"integer","assigned_to":"integer","type":"receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro","doc_number":"string","ref_number":"string","issued_at":"datetime","transact_at":"datetime","due_at":"datetime"}}],"count":"integer"} })
	 * })
	 */
	public function index($type = 'cash')
	{
		$result						= new Journal;

		$result						= $result->type($type);

		if(Input::has('search'))
		{
			$search					= Input::get('search');

			foreach ($search as $key => $value) 
			{
				switch (strtolower($key)) 
				{
					case 'id':
						$result		= $result->id($value);
						break;
					case 'companyid':
						$result		= $result->companyid($value);
						break;
					case 'transactionid':
						$result		= $result->transactionid($value);
						break;
					case 'parentaccountid':
						$result		= $result->parentaccountid($value);
						break;
					case 'accountid':
						$result		= $result->accountid($value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		if(Input::has('sort'))
		{
			$sort					= Input::get('sort');

			foreach ($sort as $key => $value) 
			{
				if(!in_array($value, ['asc', 'desc']))
				{
					return response()->json( JSend::error([$key.' harus bernilai asc atau desc.'])->asArray());
				}
				switch (strtolower($key)) 
				{
					case 'newest':
						$result		= $result->orderby('created_at', $value);
						break;
					case 'transaction':
						$result		= $result->orderby('transaction_id', $value);
						break;
					case 'debit':
						$result		= $result->orderby('debit', $value);
						break;
					case 'credit':
						$result		= $result->orderby('credit', $value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		$count						= count($result->get());

		if(Input::has('skip'))
		{
			$skip					= Input::get('skip');
			$result					= $result->skip($skip);
		}

		if(Input::has('take'))
		{
			$take					= Input::get('take');
			$result					= $result->take($take);
		}

		$result 					= $result->with(['transaction', 'details', 'details.account'])->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store Journal
	 *
	 * Store a new Journal
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null,"company_id":"integer","transaction_id":"integer","transact_at":"datetime","type":"cash|accrual", "currency":"string","notes":"text","details":{"id":"integer","journal_id":"integer","account_id":"integer","debit":"integer","credit":"integer"}})
	 *      @Response(200, body={"status": "success", "data": {"id":null,"company_id":"integer","transaction_id":"integer","transact_at":"datetime","type":"cash|accrual", "currency":"string","notes":"text","details":{"id":"integer","journal_id":"integer","account_id":"integer","debit":"integer","credit":"integer","account":{"company_id":"integer","name":"string","type":"string","code":"string"}},"transaction":{"id":"integer","amount":"integer","issued_by":"integer","company_id":"integer","assigned_to":"integer","type":"receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro","doc_number":"string","ref_number":"string","issued_at":"datetime","transact_at":"datetime","due_at":"datetime"}} })
	 *      @Response(422, body={"status": {"error": {"account invalid."}}})
	 * })
	 */
	public function post($type = 'cash')
	{
		$result						= $this->store;

		$result->fill(Input::all());

		if($result->save())
		{
			return response()->json( JSend::success($result->getData()->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError()->toArray())->asArray());
	}

	/**
	 * Delete Journal
	 *
	 * Delete a new Journal with a goods costs and service costs.
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"id":null,"company_id":"integer","transaction_id":"integer","transact_at":"datetime","type":"cash|accrual", "currency":"string","notes":"text","details":{"id":"integer","journal_id":"integer","account_id":"integer","debit":"integer","credit":"integer","account":{"company_id":"integer","name":"string","type":"string","code":"string"}},"transaction":{"id":"integer","amount":"integer","issued_by":"integer","company_id":"integer","assigned_to":"integer","type":"receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro","doc_number":"string","ref_number":"string","issued_at":"datetime","transact_at":"datetime","due_at":"datetime"}} })
	 *      @Response(422, body={"status": {"error": {"cannot delete."}}})
	 * })
	 */
	public function delete($type = 'cash')
	{
		$result				= Journal::id(Input::get('id'))->type($type)->with(['transaction', 'details', 'details.account'])->first();

		if($this->delete->delete($result))
		{
			return response()->json( JSend::success($this->delete->getData())->asArray())
					->setCallback($this->request->input('callback'));
		}

		return response()->json( JSend::error($this->delete->getError())->asArray());
	}
}
<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Services\CashNoteStore;
use App\Services\ChequeStore;
use App\Services\CreditMemoStore;
use App\Services\DebitMemoStore;
use App\Services\GiroStore;
use App\Services\InvoiceStore;
use App\Services\MemorialStore;
use App\Services\ReceiptStore;

use App\Services\CashNoteDelete;
use App\Services\ChequeDelete;
use App\Services\CreditMemoDelete;
use App\Services\DebitMemoDelete;
use App\Services\GiroDelete;
use App\Services\InvoiceDelete;
use App\Services\MemorialDelete;
use App\Services\ReceiptDelete;

use App\Entities\Transaction;

/**
 * Transaction resource representation.
 *
 * @Resource("Transactions", uri="/Transactions")
 */
class TransactionController extends Controller
{
	public function __construct(Request $request, CashNoteStore $cashnotestore, ChequeStore $chequestore, CreditMemoStore $creditmemostore, DebitMemoStore $debitmemostore, GiroStore $girostore, InvoiceStore $invoicestore, MemorialStore $memorialstore, ReceiptStore $receiptstore, CashNoteDelete $cashnotedelete, ChequeDelete $chequedelete, CreditMemoDelete $creditmemodelete, DebitMemoDelete $debitmemodelete, GiroDelete $girodelete, InvoiceDelete $invoicedelete, MemorialDelete $memorialdelete, ReceiptDelete $receiptdelete)
	{
		$this->request 				= $request;

		$this->cashnotestore		= $cashnotestore;
		$this->chequestore			= $chequestore;
		$this->creditmemostore		= $creditmemostore;
		$this->debitmemostore		= $debitmemostore;
		$this->girostore			= $girostore;
		$this->invoicestore			= $invoicestore;
		$this->memorialstore		= $memorialstore;
		$this->receiptstore			= $receiptstore;

		$this->cashnotedelete		= $cashnotedelete;
		$this->chequedelete			= $chequedelete;
		$this->creditmemodelete		= $creditmemodelete;
		$this->debitmemodelete		= $debitmemodelete;
		$this->girodelete			= $girodelete;
		$this->invoicedelete		= $invoicedelete;
		$this->memorialdelete		= $memorialdelete;
		$this->receiptdelete		= $receiptdelete;
	}

	/**
	 * Show all Transactions
	 *
	 * Get a JSON representation of all the stored Transactions.
	 *
	 * @Get("/Transactions/{type}")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"type":"all|cash_note|cheque|credit_memo|debit_memo|giro|invoice|memorial|receipt","search":[{"id":"integer", "name":string, "companyid":"integer","code":"string","type":"cash_note|cheque|credit_memo|debit_memo|giro|invoice|memorial|receipt"}],"sort":[{"newest":"asc","company":"desc","type":"desc", "code":"asc"}], "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":[{"id":"null","amount":"integer","issued_by":"integer","company_id":"integer","assigned_to":"integer","type":"receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro","doc_number":"string","ref_number":"string","issued_at":"datetime","transact_at":"datetime","due_at":"datetime","details":[{"id":"integer","transaction_id":"integer","description":"string","quantity":"integer","price":"integer","discount":"integer"}]}],"count":"integer"} })
	 * })
	 */
	public function index($type = 'all')
	{
		$result						= new Transaction;
		
		if(!str_is('all', $type))
		{
			$result					= $result->type($type);
		}

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
					case 'name':
						$result		= $result->name($value);
						break;
					case 'companyid':
						$result		= $result->companyid($value);
						break;
					case 'code':
						$result		= $result->code($value);
						break;
					case 'type':
						$result		= $result->type($value);
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
						$result		= $result->orderby('transact_at', $value);
						break;
					case 'company':
						$result		= $result->orderby('company_id', $value);
						break;
					case 'type':
						$result		= $result->orderby('type', $value);
						break;
					case 'code':
						$result		= $result->orderby('code', $value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		$count                      = count($result->get());

		if(Input::has('skip'))
		{
			$skip                   = Input::get('skip');
			$result                 = $result->skip($skip);
		}

		if(Input::has('take'))
		{
			$take                   = Input::get('take');
			$result                 = $result->take($take);
		}

		$result 					= $result->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store Transaction
	 *
	 * Store a new Transaction with a goods costs and service costs.
	 *
	 * @Post("/Transactions/{type}")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":"null","amount":"integer","issued_by":"integer","company_id":"integer","assigned_to":"integer","type":"receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro","doc_number":"string","ref_number":"string","issued_at":"datetime","transact_at":"datetime","due_at":"datetime","details":[{"id":"integer","transaction_id":"integer","description":"string","quantity":"integer","price":"integer","discount":"integer"}]}),
	 *      @Response(200, body={"status": "success", "data": {"id":"null","amount":"integer","issued_by":"integer","company_id":"integer","assigned_to":"integer","type":"receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro","doc_number":"string","ref_number":"string","issued_at":"datetime","transact_at":"datetime","due_at":"datetime","details":[{"id":"integer","transaction_id":"integer","description":"string","quantity":"integer","price":"integer","discount":"integer"}]}}),
	 *      @Response(422, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post($type = 'all')
	{
		switch (strtolower($type)) 
		{
			case 'cash_note':
				$result				= $this->cashnotestore;
				break;
			case 'cheque':
				$result				= $this->chequestore;
				break;
			case 'credit_memo':
				$result				= $this->creditmemostore;
				break;
			case 'debit_memo':
				$result				= $this->debitmemostore;
				break;
			case 'giro':
				$result				= $this->girostore;
				break;
			case 'invoice':
				$result				= $this->invoicestore;
				break;
			case 'memorial':
				$result				= $this->memorialstore;
				break;
			case 'receipt':
				$result				= $this->receiptstore;
				break;
			default:
				return response()->json( JSend::error(['Tipe akun tidak valid'])->asArray());
				break;
		}
		

		$result->fill(Input::all());

		if($result->save())
		{
			return response()->json( JSend::success($result->getData()->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError()->toArray())->asArray());
	}

	/**
	 * Delete Transaction
	 *
	 * Delete a new Transaction with a goods costs and service costs.
	 *
	 * @Delete("/transactions/{type}")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"id":"null","amount":"integer","issued_by":"integer","company_id":"integer","assigned_to":"integer","type":"receipt|cash_note|cheque|invoice|credit_memo|debit_memo|memorial|giro","doc_number":"string","ref_number":"string","issued_at":"datetime","transact_at":"datetime","due_at":"datetime","details":[{"id":"integer","transaction_id":"integer","description":"string","quantity":"integer","price":"integer","discount":"integer"}]} }),
	 *      @Response(422, body={"status": {"error": {"cannot delete."}}})
	 * })
	 */
	public function delete($type = 'all')
	{
		switch (strtolower($type)) 
		{
			case 'cash_note':
				$this->delete	= $this->cashnotedelete;
				break;
			case 'cheque':
				$this->delete	= $this->chequedelete;
				break;
			case 'credit_memo':
				$this->delete	= $this->creditmemodelete;
				break;
			case 'debit_memo':
				$this->delete	= $this->debitmemodelete;
				break;
			case 'giro':
				$this->delete	= $this->girodelete;
				break;
			case 'invoice':
				$this->delete	= $this->invoicedelete;
				break;
			case 'memorial':
				$this->delete	= $this->memorialdelete;
				break;
			case 'receipt':
				$this->delete	= $this->receiptdelete;
				break;
			default:
				return response()->json( JSend::error(['Tipe akun tidak valid'])->asArray());
				break;
		}

		$result					= Transaction::id(Input::get('id'))->type(strtolower($type))->first();

		if($this->delete->delete($result))
		{
			return response()->json( JSend::success($this->delete->getData())->asArray())
					->setCallback($this->request->input('callback'));
		}

		return response()->json( JSend::error($this->delete->getError())->asArray());
	}
}
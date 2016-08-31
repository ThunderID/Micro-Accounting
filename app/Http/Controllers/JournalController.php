<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

use App\Services\JournalStore;
use App\Entities\Journal;

/**
 * Journal resource representation.
 *
 * @Resource("Journals", uri="/Journals")
 */
class JournalController extends Controller
{
	public function __construct(Request $request, JournalStore $store)
	{
		$this->request 				= $request;
		$this->store				= $store;
	}

	/**
	 * Show all Journals
	 *
	 * Get a JSON representation of all the stored Journals.
	 *
	 * @Get("/Journals")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"type":"","search":[{"name":string, "companyid":"integer","code":"string","type":"asset|liability|equity|income|expense"}],"sort":[{"newest":"asc","company":"desc","type":"desc", "code":"asc"}], "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":[{"id":null,"company_id":"integer","name":"string","code":"string","type":"string"}],"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new Journal;

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

		$result 					= $result->with(['parentaccount', 'account'])->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store Journal
	 *
	 * Store a new Journal with a goods costs and service costs.
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null,"company_id":"integer","name":"string","code":"string","type":"string"}),
	 *      @Response(200, body={"status": "success", "data": {"id":null,"company_id":"integer","name":"string","code":"string","type":"string"}}),
	 *      @Response(422, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post()
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
	 *      @Response(200, body={"status": "success", "data": {"id":null,"ref_number":"string","issued_by":"integer","company_id":"integer","customer_id":"integer","issued_at":"datetime","due_at":"datetime","goods":[{"id":null,"Journal_id":"integer","product_id":"integer","quantity":"integer","price":"double","discount":"double"}],"services":[{"id":null,"Journal_id":"integer","service_id":"integer","price":"double","discount":"double"}]}}),
	 *      @Response(422, body={"status": {"error": {"cannot delete."}}})
	 * })
	 * Event created : tlab.Journal.deleted
	 */
	public function delete()
	{
		$result				= Journal::id(Input::get('id'))->first();

		if($this->deleted->delete($result))
		{
			$connection 	= new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
			$channel 		= $connection->channel();
			
			$routing_key 	= 'tlab.journal.deleted';

			$channel->exchange_declare('topic_logs', 'topic', false, false, false);

			$data 		= '{"date": "'.$this->deleted->getData()['issued_at'].'","amount": "'.abs($this->deleted->getData()['amount']).'"}';
			$msg 		= new AMQPMessage($data);

			$channel->basic_publish($msg, 'topic_logs', $routing_key);

			echo " [x] Sent ", $data, "\n";

			$channel->close();
			$connection->close();

			return response()->json( JSend::success($this->deleted->getData())->asArray())
					->setCallback($this->request->input('callback'));
		}

		return response()->json( JSend::error($this->deleted->getError())->asArray());
	}
}
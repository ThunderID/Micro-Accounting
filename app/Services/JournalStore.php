<?php

namespace App\Services;

use Illuminate\Support\MessageBag;

use App\Entities\Journal;

use App\Contracts\JournalStoreInterface;

use App\Contracts\Policies\ValidatingJournalInterface;
use App\Contracts\Policies\ProceedJournalInterface;
use App\Contracts\Policies\EffectJournalInterface;

class JournalStore implements JournalStoreInterface 
{
	protected $journal;
	protected $errors;
	protected $saved_data;
	protected $pre;
	protected $pro;
	protected $post;

	/**
	 * construct function, iniate error
	 *
	 */
	function __construct(ValidatingJournalInterface $pre, ProceedJournalInterface $pro, EffectJournalInterface $post)
	{
		$this->errors 		= new MessageBag;
		$this->pre 			= $pre;
		$this->pro 			= $pro;
		$this->post 		= $post;
	}

	/**
	 * return errors
	 *
	 * @return MessageBag
	 **/
	function getError()
	{
		return $this->errors;
	}

	/**
	 * return saved_data
	 *
	 * @return saved_data
	 **/
	function getData()
	{
		return $this->saved_data;
	}

	/**
	 * Checkout
	 *
	 * 1. Call Class fill
	 * 
	 * @return Response
	 */
	public function fill(array $journal)
	{
		$this->journal 		= $journal;
	}

	/**
	 * Save
	 *
	 * Here's the workflow
	 * 
	 * @return Response
	 */
	public function save()
	{
		$pre_journal			= Journal::id($this->journal['id'])->first();
	
		/** PREPROCESS */

		//1. validate Journal
		$this->pre->validatejournal($this->journal); 

		if($this->pre->errors->count())
		{
			$this->errors		= $this->pre->errors;

			return false;
		}

		\DB::BeginTransaction();

		/** PROCESS */

		//2. Store Data Journal
		$this->pro->storejournal($this->journal); 
		
		if($this->pro->errors->count())
		{
			\DB::rollback();

			$this->errors 		= $this->pro->errors;

			return false;
		}

		\DB::commit();

		//3. Return Journal Model Object
		$pro_journal			= Journal::id($this->pro->journal['id'])->first();

		$this->saved_data		= $pro_journal;

		return true;
	}
}

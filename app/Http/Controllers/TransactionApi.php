<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Libs\ClearSettle\Resource\ApiClientManager;

class TransactionApi extends Controller
{    
    
    /**
     * @var \App\Libs\ClearSettle\Resource\ApiClientManager
     */
    protected $clientManager;
    
        /**
         * 
         * @param \App\Libs\ClearSettle\Resource\ApiClientManager $manager
         */   
        public function __construct(ApiClientManager $manager) 
        {
            $this->clientManager = $manager;
        }        
        
        /**
         * Create Transaction Request
         * 
         * @return \App\Libs\ClearSettle\Resource\Request\Transaction
         */
        protected function createRequest()
        {
            return $this->clientManager->createNewRequest('transaction');
        }
        
        
        public function postReport()
        {
            
        }
        

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            return 'I am Index';
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            //
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function edit($id)
        {
            //
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, $id)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function destroy($id)
        {
            //
        }
}

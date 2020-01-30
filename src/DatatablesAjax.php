<?php

namespace Amprest\LaravelDatatables;

use Illuminate\Http\Request;

class DatatablesAjax
{
    /**
     *  Initialize the class instance
     *  @author Alvin Gichira Kaburu
     *  @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     *  Process a datatables request and return JSON payload
     *  @author Alvin Gichira Kaburu
     *  @param \Illuminate\Http\Request $request
     *  @param Class $model
     *  @return \Illuminate\Http\Response
     */
    public function render($model)
    {
        //  Get the request 
        $request = $this->request;

        //  Get the model
        $model = app($model); 
        
        //  Get the total number of model instances
        $total = $model->count();

        //  Create a builder query
        $filtered = $model->query();

        //  Get the number of items per page, the index to start with 
        //  then finally launch a query builder
        $builder = $model->offset($request->start)
            ->limit($request->length);

        //  Handle ordering of columns
        $this->handleColumnOrdering($request, $builder);

        //  Handle searching of data
        $this->handleSearching($request, $builder, $filtered);

        //  Return a json response
        return response()->json([
            'draw' => $request->draw,  
            'recordsTotal' => $total,  
            'recordsFiltered' => $filtered->count(), 
            'data' => $this->processBuilderData($request, $builder),  
        ]);
    }

    /**
     *  Handle the ordering of columns
     *  @author Alvin Gichira Kaburu
     *  @param \Illuminate\Http\Request $request
     *  @param address &$builder
     *  @return null
     */
    public function handleColumnOrdering(Request $request, &$builder)
    {
        // Check if filters have been defined
        if($request->filters && $request->order) {
            //  Loop through the ordering object
            foreach($request->order as $option) {
                //  Check if an order direction has been defined
                if($option['dir']) {
                    //  Get the column that is being ordered
                    $column = $request['filters'][ (int) $option['column'] ]['server'];
                    $builder = $builder->orderBy($column, $option['dir']);
                }
            }
        }
    }

    /**
     *  Handle processing of search results
     *  @author Alvin Gichira Kaburu
     *  @param \Illuminate\Http\Request $request
     *  @param address &$builder
     *  @param address &$filtered
     *  @return null
     */
    public function handleSearching(Request $request, &$builder, &$filtered)
    {
        //  Get the search value from the request
        $search = $request->input('search.value'); 

        //  Loop through all columns
        foreach($request->columns as $column) {
            //  Derive the column data from the filters object
            $record = collect($request->filters)->filter(function($filter) use ($column){
                return $filter['name'] == $column['data'];
            })->first();

            //  If a general search criteria has been defined
            if($request->input('search.value') && $record){ 
                $this->getSearchedGeneralData($record, $search, $builder, $filtered);
            } else {
                // Get the column search value
                $search = $column['search']['value'];
                if($search) {
                    $this->getSearchedColumnData($record, $search, $builder, $filtered );
                }
            }
        }
    }

    /**
     *  Handle processing of general search results
     *  @author Alvin Gichira Kaburu
     *  @param mixed $column
     *  @param string $search
     *  @param address &$builder
     *  @param address &$filtered
     *  @return null
     */
    public function getSearchedGeneralData($column, $search, &$builder, &$filtered)
    {
        //  Get the builder results 
        $builder = $builder->where($column['server'], 'LIKE', "%{$search}%");

        //  Get the number of filtered records
        $filtered = $filtered->where($column['server'], 'LIKE', "%{$search}%");
    }

    /**
     *  Handle processing of column search results
     *  @author Alvin Gichira Kaburu
     *  @param mixed $record
     *  @param string $search
     *  @param address &$builder
     *  @param address &$filtered
     *  @return null
     */
    public function getSearchedColumnData($record, $search, &$builder, &$filtered )
    {
        //  Determine the matching criteria
        if($record && ( $record['type'] == 'select' )) $match = '=';
        else {
            $match = 'LIKE';
            $search = "%{$search}%";
        }

        //  Make the query
        $builder = $builder->where($record['server'], $match ,$search);

        //  Get the new total filtered value
        $filtered = $filtered->where($record['server'], $match ,$search);
    }

    /**
     *  Handle processing of general search results
     *  @author Alvin Gichira Kaburu
     *  @param \Illuminate\Http\Request $request
     *  @param $builder
     *  @return array
     */
    public function processBuilderData(Request $request, $builder)
    {
        //  Create a data countainer
        $data = [];

        //  Get results from the builder query
        $results = $builder->get();
        
        //  Check if the result is empty  
        if($results->isNotEmpty()) {
            //  Loop through the builder results
            foreach ($results as $result) {
                $container = [];
                foreach($request->filters as $column) {
                    if($column['server']) {
                        $container[ $column['data'] ] = $result->{ $column['server'] };
                    }
                }
                array_push($data, $container);
            }
        }
        return $data;
    }
}
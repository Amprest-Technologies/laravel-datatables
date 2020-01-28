<?php

namespace Amprest\LaravelDatatables\Controllers;

use Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Amprest\LaravelDatatables\Models\Configuration;

class ConfigurationController extends Controller 
{
    /**
     * List all table configurations in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @return \Illuminate\Support\Facades\View
     */
    public function index()
    {
        return package_view('configurations.index', [
            'configurations' => Configuration::all(),
        ]); 
    }

    /**
     * Store a new table configuration in the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Facades\View
     */
    public function store(Request $request)
    {
        //  Merge the request parameters
        $request->merge([ 
            'identifier' => $identifier = Str::slug($request->identifier),
            'payload' => array_merge([ 'id' => $identifier ], config('datatables')),
        ]);

        //  Validate the request
        $this->validateRequest($request);

        //  Create the configuration
        $configuration = Configuration::create($request->all());

        //  Redirect to the configuration edit page
        return redirect()->route('datatables.configurations.edit', [
            'configuration' => $configuration,
        ]);
    }

    /**
     * Edit/Show a particular table configuration from the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param \Amprest\LaravelDatatables\Models\Configuration $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function edit(Configuration $configuration)
    {
        return package_view('configurations.edit', [
            'configuration' => $configuration,
            'identifier' => $configuration->identifier, 
            'configurations' => $configuration->payload,
        ]); 
    }

    /**
     * Edit/Show a particular table configuration from the database
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Amprest\LaravelDatatables\Models\Configuration $configuration
     * @return \Illuminate\Support\Facades\View
     */
    public function update(Request $request, Configuration $configuration)
    {
        return $request->all();
    }

    /**
     *  Validate the request.
     *
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     * @param  \Illuminate\Http\Request  $request
     * @param \Amprest\LaravelDatatables\Models\Configuration $configuration
     * @return \Illuminate\Http\Response
     */
    public function validateRequest(Request $request, Configuration $configuration = null)
    {
        //  Define the unique rule
        $unique = Rule::unique(  with(new Configuration)->getTable() )
            ->ignore($configuration->id ?? null);

        //  Return the validator instance
        return Validator::make($request->all(), [
            'identifier' => [ 'required', 'max:30', 'min:5', $unique ],
            'payload' => [ 'required', 'array' ],
        ])->validate();
    }
}
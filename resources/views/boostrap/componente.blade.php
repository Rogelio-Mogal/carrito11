@extends('layouts.app')

@section('css')

@stop

@section('content')

    <div class="p-3 sm:ml-64 dark:bg-gray-900 text-black dark:text-white">
        <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">

            <p class="text-2xl font-semibold leading-normal text-gray-900 dark:text-white">Fomulario.</p>



            <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
                <!-- Precio -->
                <div class="sm:col-span-12 lg:col-span-3 md:col-span-6">
                    <label for="precio" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Precio</label>
                    <select id="precio" name="precio"
                        class="block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="Interno">Interno</option>
                        <option value="CVA">CVA</option>
                    </select>
                </div>

                <!-- Desde -->
                <div class="sm:col-span-12 lg:col-span-3 md:col-span-4">
                    <label for="desde" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Desde</label>
                    <input type="text" id="desde" name="desde"
                        class="block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Hasta -->
                <div class="sm:col-span-12 lg:col-span-3 md:col-span-4">
                    <label for="hasta" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Hasta</label>
                    <input type="text" id="hasta" name="hasta"
                        class="block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- % Público -->
                <div class="sm:col-span-12 lg:col-span-3 md:col-span-2">
                    <label for="publico" class="block text-sm font-medium text-gray-700 dark:text-gray-200">%
                        Público</label>
                    <input type="text" id="publico" name="publico"
                        class="block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- % Medio mayoreo -->
                <div class="sm:col-span-12 lg:col-span-5 md:col-span-2">
                    <label for="medio-mayoreo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">% Medio
                        mayoreo</label>
                    <input type="text" id="medio-mayoreo" name="medio-mayoreo"
                        class="block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- % Mayoreo -->
                <div class="sm:col-span-12 lg:col-span-5 md:col-span-2">
                    <label for="mayoreo" class="block text-sm font-medium text-gray-700 dark:text-gray-200">%
                        Mayoreo</label>
                    <input type="text" id="mayoreo" name="mayoreo"
                        class="block w-full mt-1 p-2.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Botón Agregar -->
                <div class="sm:col-span-1 lg:col-span-2 md:col-span-1 ">
                    <label for="boton" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        &nbsp;</label>
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Default</button>                    
                </div>

            </div>
        </div>


        <br />

        <div>
            <table id="example" class="table table-striped nowrap" style="width:100%">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2">First name</th>
                        <th class="px-4 py-2">Last name</th>
                        <th class="px-4 py-2">Position</th>
                        <th class="px-4 py-2">Office</th>
                        <th class="px-4 py-2">Age</th>
                        <th class="px-4 py-2">Start date</th>
                        <th class="px-4 py-2">Salary</th>
                        <th class="px-4 py-2">Extn.</th>
                        <th class="px-4 py-2">E-mail</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                    <tr>
                        <td>Tiger</td>
                        <td>Nixon</td>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>61</td>
                        <td>2011-04-25</td>
                        <td>$320,800</td>
                        <td>5421</td>
                        <td>t.nixon@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Garrett</td>
                        <td>Winters</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>63</td>
                        <td>2011-07-25</td>
                        <td>$170,750</td>
                        <td>8422</td>
                        <td>g.winters@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Ashton</td>
                        <td>Cox</td>
                        <td>Junior Technical Author</td>
                        <td>San Francisco</td>
                        <td>66</td>
                        <td>2009-01-12</td>
                        <td>$86,000</td>
                        <td>1562</td>
                        <td>a.cox@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Cedric</td>
                        <td>Kelly</td>
                        <td>Senior Javascript Developer</td>
                        <td>Edinburgh</td>
                        <td>22</td>
                        <td>2012-03-29</td>
                        <td>$433,060</td>
                        <td>6224</td>
                        <td>c.kelly@datatables.net</td>
                    </tr>
                    <tr>
                        <td>Airi</td>
                        <td>Satou</td>
                        <td>Accountant</td>
                        <td>Tokyo</td>
                        <td>33</td>
                        <td>2008-11-28</td>
                        <td>$162,700</td>
                        <td>5407</td>
                        <td>a.satou@datatables.net</td>
                    </tr>


                </tbody>
            </table>

            {{-- <table id="example" class="table table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                        <th>Extn.</th>
                        <th>E-mail</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tiger</td>
                        <td>Nixon</td>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>61</td>
                        <td>2011-04-25</td>
                        <td>$320,800</td>
                        <td>5421</td>
                        <td>t.nixon@datatables.net</td>
                    </tr>
                    <!-- Agrega más filas según sea necesario -->
                </tbody>
            </table>
            --}}
        </div>

        <!--</div>-->








    @endsection

    @section('js')
        <script>
            $(document).ready(function() {

                new DataTable('#example', {
                    responsive: true,
                    "language": {
                        "url": "{{ asset('/json/i18n/es_es.json') }}"
                    },
                });

            });
        </script>
    @stop

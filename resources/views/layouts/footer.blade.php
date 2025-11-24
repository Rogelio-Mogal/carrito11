  {{-- 
   <!--popper -->
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
--}}
    
    <!-- Flowbite -->
    <script src="{{ mix('node_modules/flowbite/dist/flowbite.min.js') }}"></script>


    <!--datatables -->
    <script src="{{ asset('datatable/js/datatables.min.js') }}"></script>

    <!--select2 -->
    <script src="{{ asset('select2/select2.min.js') }}"></script>

    



    @livewireScripts

    <!-- Mensajes de alerta -->
    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'bg-red-600 text-white hover:bg-red-700 rounded-lg px-4 py-2'
                }
            });
        </script>
    @elseif(session('swal'))
        <script>
            Swal.fire( {!! json_encode(session('swal')) !!} )
        </script>
    @elseif(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session("success") }}',
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'bg-green-600 text-white hover:bg-green-700 rounded-lg px-4 py-2'
                }
            });
        </script>
    @endif

    @yield('js')


    <script>
        // APERTURA EL SUBMENU 
        document.addEventListener('DOMContentLoaded', () => {
            const currentRoute = "{{ request()->route()->getName() }}"; // Obtiene el nombre de la ruta actual
            const routesToCheck = {
                'dropdown-cuenta': [
                    'admin.perfil.*',
                    'admin.caja.turno*',
                ],
                'dropdown-finanzas': [
                    'admin.forma.pago.*',
                    'admin.tipo.gasto.*',
                    'admin.gastos.*',
                    'admin.asignar.gasto.*',
                ],
                'dropdown-clientes': [
                    'admin.clientes.*',
                    'admin.venta.credito.*',
                    'admin.nota.credito.*'
                ],
                'dropdown-servicios': [
                    'admin.reparacion.*',
                    'admin.garantias.*',
                    'admin.anticipo.*',
                    'admin.apartado.*'
                ],
                'dropdown-productos': [
                    'admin.proveedores.*',
                    'admin.producto.caracteristica.*',
                    'admin.atributos.*',
                    'admin.familia.atributos.*',
                    'admin.producto.servicio.*',
                    'admin.precios.*',
                    'admin.compras.*',
                ],
                'dropdown-documentos': [
                    'admin.cotizacion.*',
                    'admin.ticket.alterno.*',
                    'admin.nota.venta.*',
                    'admin.nota.pc.venta.*',
                ],
            };

            // Función para verificar si la ruta actual coincide con alguna de las rutas especificadas con comodines
            function routeMatches(route, patterns) {
                return patterns.some(pattern => {
                    const regex = new RegExp('^' + pattern.replace(/\./g, '\\.').replace(/\*/g, '.*') + '$');
                    return regex.test(route);
                });
            }

            document.querySelectorAll('button[data-target]').forEach(button => {
                const targetMenu = button.getAttribute('data-target');

                if (routesToCheck[targetMenu] && routeMatches(currentRoute, routesToCheck[targetMenu])) {
                    const menu = document.getElementById(targetMenu);
                    if (menu) {
                        menu.classList.remove('hidden');
                        button.setAttribute('aria-expanded', 'true');
                        //button.querySelector('svg').classList.add('rotate-180'); // Rota la flecha hacia abajo
                    }
                }
            });
        });


        /*document.addEventListener('DOMContentLoaded', () => {
            const currentRoute = "{{ request()->route()->getName() }}"; // Obtiene el nombre de la ruta actual
            const routesToCheck = [
                'admin.proveedores.*',
                'admin.producto.caracteristica.*',
                'admin.producto.servicio.*',
                'admin.precios.*',
                'admin.compras.*',
            ];
            */

            // Función para verificar si la ruta actual coincide con alguna de las rutas especificadas con comodines
            //function routeMatches(route, patterns) {
            //    return patterns.some(pattern => {
            //        const regex = new RegExp('^' + pattern.replace(/\./g, '\\.').replace(/\*/g, '.*') + '$');
            //        return regex.test(route);
            //    });
           // }
            /*
            document.querySelectorAll('button[data-target]').forEach(button => {
                const targetId = button.getAttribute('data-target');
                const submenu = document.getElementById(targetId);

                // Abre el submenú si la ruta actual coincide con alguna de las rutas especificadas
                if (routeMatches(currentRoute, routesToCheck)) {
                    submenu.classList.remove('hidden');
                    button.setAttribute('aria-expanded', 'true');
                }

                button.addEventListener('click', () => {
                    const isExpanded = button.getAttribute('aria-expanded') === 'true';
                    submenu.classList.toggle('hidden', isExpanded);
                    button.setAttribute('aria-expanded', !isExpanded);
                });
            });
        });
        */
    </script>
    </body>

    </html>

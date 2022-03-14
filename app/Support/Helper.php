<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

class Helper
{
    /**
    * Returns a Link tag for import datatables.css
    *
    * @return string
    */
    public static function dataTablesCSS() {
        $linkDatablesCSS = asset('vendor/datatables/css/dataTables.bootstrap4.min.css');

        return "<link rel='stylesheet' href='$linkDatablesCSS'>";
    }

    /**
     * Returns a Script tag for import datatables.js
     *
     * @return string
     */
    public static function dataTablesJS() {
        $linkDatatableJS        = asset('vendor/datatables/js/jquery.dataTables.min.js');
        $linkDatatableBootstrap = asset('vendor/datatables/js/dataTables.bootstrap4.min.js');

        return "<script src='$linkDatatableJS'></script>
                <script src='$linkDatatableBootstrap'></script>";
    }

    /**
     * Returns a JS code for Sweet Alert Confirm
     *
     * @param  string  $selector
     * @param  string  $msg
     * @param  string  $formSelector
     *
     * @return string
     */
    public static function swalConfirm($selector, $msg=null, $formSelector='') {

        $msg = $msg ?: 'Este registro será suspendido en caso de estar en uso y eliminado en caso contrario';

        return <<<HTML
            <script>
                $(document).on('click', '$selector', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Está seguro?',
                        text: '$msg',
                        icon: 'warning',
                        confirmButtonText: 'Sí!',
                        cancelButtonText: 'Cancelar',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                    })
                    .then ((result) => {
                        if (result.value) {
                            form = "$formSelector"
                                ? $('$formSelector')
                                : $('#'+$(this).attr('form-target'));

                            if (this.name) {
                                form.append(`<input name='\${this.name}' value='\${this.value}'>`)
                            }

                            form.submit();
                        }
                    })
                });
            </script>
HTML;
    }

    /**
     * Returns a JS code for init DataTables
     *
     * @param  string  $selector
     * @param  JSON  $options
     *
     * @return string
     */
    public static function dataTables($selector, $options='{}') {

        $linkDatablesSpanish = asset('vendor/datatables/js/i18n/Spanish.json');

        return <<<HTML
            <script>
                $(document).ready(function() {
                    $("$selector").DataTable({
                        ...{
                            language: {
                                url: "$linkDatablesSpanish"
                            },
                        },
                        ...$options
                    });
                } );
            </script>
HTML;
    }

    /**
     * Check if a route is private
     *
     * @param string $routeName
     *
     * @return boolean
     */
    public static function isPrivateRoute($routeName)
    {
        $route = Route::getRoutes()->getByName($routeName);

        if ($route) {
            // Check if the access middleware is attached to the route
            return in_array('access', $route->gatherMiddleware());
        }

        return false;
    }
}

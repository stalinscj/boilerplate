<?php

namespace Tests\Unit\Support;

use Tests\TestCase;
use App\Support\Helper;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Testing\WithFaker;

class HelperTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function must_return_link_tag_to_load_datatables_css()
    {
        $linkDatablesCSS = asset('vendor/datatables/css/dataTables.bootstrap4.min.css');

        $tag = Helper::dataTablesCSS();

        $this->assertEquals(
            "<link rel='stylesheet' href='$linkDatablesCSS'>",
            $tag
        );
    }

    /**
     * @test
     */
    public function must_return_script_tags_to_load_datatables_js()
    {
        $linkDatatableJS        = asset('vendor/datatables/js/jquery.dataTables.min.js');
        $linkDatatableBootstrap = asset('vendor/datatables/js/dataTables.bootstrap4.min.js');

        $tags = Helper::dataTablesJS();

        $this->assertEquals(
            "<script src='$linkDatatableJS'></script>
                <script src='$linkDatatableBootstrap'></script>",
            $tags
        );
    }

    /**
     * @test
     */
    public function must_return_a_js_code_with_sweetalert_confirm()
    {
        $selector     = $this->faker->domainName;
        $msg          = $this->faker->sentence;
        $formSelector = $this->faker->domainName;

        $swalConfirm = Helper::swalConfirm($selector, $msg, $formSelector);

        $this->assertEquals(
            "            <script>
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
                            form = \"$formSelector\"
                                ? $('$formSelector')
                                : $('#'+$(this).attr('form-target'));

                            if (this.name) {
                                form.append(`<input name='\${this.name}' value='\${this.value}'>`)
                            }

                            form.submit();
                        }
                    })
                });
            </script>",
            $swalConfirm
        );
    }

    /**
     * @test
     */
    public function must_return_a_js_code_for_init_datatables()
    {
        $selector = $this->faker->domainName;
        $options = "{key: value}";

        $linkDatablesSpanish = asset('vendor/datatables/js/i18n/Spanish.json');

        $datatables = Helper::dataTables($selector, $options);

        $this->assertEquals(
            "            <script>
                $(document).ready(function() {
                    $(\"$selector\").DataTable({
                        ...{
                            language: {
                                url: \"$linkDatablesSpanish\"
                            },
                        },
                        ...{key: value}
                    });
                } );
            </script>",
            $datatables
        );
    }

    /**
     * @test
     */
    public function can_check_if_a_route_is_private_or_not()
    {
        Route::name('private.route')
            ->get('private-route', function () {return true;})
            ->middleware('access');

        Route::name('public-route', function () {return true;})
            ->get('public.route');

        $this->assertTrue(Helper::isPrivateRoute('private.route'));
        $this->assertFalse(Helper::isPrivateRoute('public.route'));
    }
}

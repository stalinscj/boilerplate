<?php

namespace Tests\Unit\Traits;

use Tests\TestCase;
use App\Traits\SecureDeletes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SecureDeletesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Schema::create('independent_models', function ($table) {
            $table->id();
            $table->softDeletes();
        });

        Schema::create('dependent_models', function ($table) {
            $table->id();
            $table->foreignId('independent_model_id')->constrained();
        });
    }

    /**
     * @test
     */
    public function independent_models_are_marked_as_deleted_when_has_dependent_models()
    {
        $independentModel = IndependentModel::create();
        $independentModel->dependentModels()->create();

        $independentModel->secureDelete();

        $this->assertTrue(IndependentModel::withTrashed()->whereNotNull('deleted_at')->exists());
    }

    /**
     * @test
     */
    public function independent_models_are_deleted_when_dont_has_dependent_models()
    {
        $independentModel = IndependentModel::create();

        $independentModel->secureDelete();

        $this->assertFalse(IndependentModel::withTrashed()->exists());
    }
}


class IndependentModel extends Model
{
    use SoftDeletes, SecureDeletes;

    public $timestamps = false;

    public function dependentModels()
    {
        return $this->hasMany(DependentModel::class);
    }
}


class DependentModel extends Model
{
    public $timestamps = false;

    public function independentModel()
    {
        return $this->belongsTo(IndependentModel::class);
    }
}

<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

trait SecureDeletes
{
    use SoftDeletes;

    /**
     * Force delete only when there is no reference to other models
     * otherwise it will be soft delete
     *
     * @throws Exception
     *
     * @return bool
     */
    public function secureDelete()
    {
        try {
            return (clone $this)->forceDelete();
        } catch (Exception $exception) {
            $driver = DB::connection()->getDriverName();

            $integrityConstraintViolation = false;

            switch ($driver) {
                case 'pgsql':
                    $integrityConstraintViolation = $exception->getCode() == 23503 && $exception->errorInfo[1] == 7;
                    break;

                case 'mysql':
                    $integrityConstraintViolation = $exception->getCode() == 23000 && $exception->errorInfo[1] == 1451;
                    break;

                case 'sqlite':

                    $integrityConstraintViolation = $exception->getCode() == 23000 && $exception->errorInfo[1] == 19;
                    break;

                default:
                    break;
            }

            if ($integrityConstraintViolation) {
                return $this->delete();
            }

            throw $exception;
        }
    }
}

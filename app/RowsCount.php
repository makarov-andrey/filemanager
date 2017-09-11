<?php

namespace App;

use Doctrine\DBAL\Exception\DatabaseObjectExistsException;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RowsCount extends Model
{
    protected $primaryKey = 'table_name';
    public $incrementing = false;
    public $timestamps = false;

    const ON_INSERT_TRIGGER_PREFIX = 'tr_count_rows_on_insert_';
    const ON_DELETE_TRIGGER_PREFIX = 'tr_count_rows_on_delete_';

    /**
     * save new model and create counter trigger
     *
     * @return $this
     */
    public function up ()
    {
        $this->recount();
        $this->save();
        $this->enableTriggers();
        return $this;
    }

    /**
     * drop counter trigger and delete model from database
     *
     * @return bool|null
     */
    public function delete ()
    {
        $this->disableTriggers();
        return parent::delete();
    }

    /**
     * create counter trigger
     *
     * @return $this
     * @throws \Exception
     */
    public function enableTriggers ()
    {
        if (!$this->exists) {
            throw new \Exception('Instance does not exist in database');
        }
        $this->disableTriggers();
        DB::unprepared('
            CREATE TRIGGER ' . $this->getOnInsertTriggerName() . ' AFTER INSERT ON `' . $this->table_name . '` FOR EACH ROW
            BEGIN
                UPDATE `' . $this->getTable() . '` 
                SET `count` = `count` + 1 
                WHERE `table_name` = "files";
            END;
            
            CREATE TRIGGER ' . $this->getOnDeleteTriggerName() . ' AFTER DELETE ON `' . $this->table_name . '` FOR EACH ROW
            BEGIN
                UPDATE `' . $this->getTable() . '` 
                SET `count` = `count` - 1 
                WHERE `table_name` = "files";
            END
        ');
        return $this;
    }

    /**
     * drop counter trigger
     *
     * @return $this
     */
    public function disableTriggers ()
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS ' . $this->getOnInsertTriggerName() . ';
            DROP TRIGGER IF EXISTS ' . $this->getOnDeleteTriggerName() . ';
        ');
        return $this;
    }

    /**
     * @return string
     */
    public function getOnInsertTriggerName ()
    {
        return static::ON_INSERT_TRIGGER_PREFIX . $this->table_name;
    }

    /**
     * @return string
     */
    public function getOnDeleteTriggerName ()
    {
        return static::ON_DELETE_TRIGGER_PREFIX . $this->table_name;
    }

    /**
     * count rows by real SELECT COUNT(*) query and sets result to the property 'count'
     *
     * @return $this
     */
    public function recount()
    {
        $this->count = DB::table($this->table_name)->count();
        return $this;
    }
}

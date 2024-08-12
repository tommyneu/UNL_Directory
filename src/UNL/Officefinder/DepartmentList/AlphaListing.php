<?php
class UNL_Officefinder_DepartmentList_AlphaListing extends FilterIterator
{
    public $options = array('q'=>'');

    function __construct($options = array())
    {
        $this->options = $options + $this->options;

        parent::__construct($this->getIterator());
    }

    protected function getIterator()
    {
        $records = array();
        $mysqli = UNL_Officefinder::getDB();
        $sql = $this->getSQL();
        if ($result = $mysqli->query($sql)) {
            while($row = $result->fetch_array(MYSQLI_NUM)) {
                $records[] = $row;
            }
        }
        return new ArrayIterator($records);
    }

    protected function getSQL()
    {
        $sql = 'SELECT DISTINCT departments.id AS id, departments.name AS name
                FROM departments
                WHERE org_unit IS NOT NULL
                    UNION
                SELECT department_aliases.department_id as id, CONCAT(department_aliases.name, " (see ", departments.name, ")") as name
                FROM department_aliases, departments
                WHERE department_aliases.department_id = departments.id

                ORDER BY name;';
        return $sql;
    }

    public function accept(): bool
    {
        if ($this->current()->department->hasChildren()
            || isset($this->current()->department->phone)) {
            return true;
        }
        return false;
    }

    public function current(): mixed
    {
        $row = parent::current();
        return new UNL_Officefinder_DepartmentList_AlphaListing_Department($row[1],
            UNL_Officefinder_Department::getByID($row[0]),
            $this->options);
    }
}

<?php
class UNL_Officefinder_TreeView extends FilterIterator
{
    public function __construct($options = array())
    {
        // retrieve the left and right value of the $root node
        $root = UNL_Officefinder_Department::getByorg_unit('50000001');
        //$root = UNL_Officefinder_Department::getByname('Office of University Communications');
        $iterator = new UNL_Officefinder_DepartmentList(array($root));

        parent::__construct(new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST));
    }

    public function accept(): bool
    {
        if ($this->getInnerIterator()->current()->isOfficialDepartment()
            && strlen($this->getInnerIterator()->current()->org_unit) == 8) {
            return true;
        }
        return false;
    }
}

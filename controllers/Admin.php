<?php
class Admin extends Controller{
    function show(){
        $obj=$this->model("AdProductTypeModel");
        $data=$obj->all("tblloaisp");
        $this->view("adminPage",["page"=>"ProductTypeView","productList"=>$data]);
    }
}
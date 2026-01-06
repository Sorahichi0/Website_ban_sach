<?php
class ProductType extends Controller{
    public function show(){
        $obj=$this->model("AdProductTypeModel");
        $data=$obj->all("tblloaisp");
        //$this->view("admin",["page"=>"listProduct","productList"=>$data]);
        $this->view("adminPage",["page"=>"ProductTypeView","productList"=>$data]);
    }
    public function delete($id){
        $obj=$this->model("AdProductTypeModel");
        $obj->delete("tblloaisp",$id);
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    public function create(){
        $txt_maloaisp =isset($_POST["txt_maloaisp"])?$_POST["txt_maloaisp"]:"";
        $txt_tenloaisp =isset($_POST["txt_tenloaisp"])?$_POST["txt_tenloaisp"]:"";
        $txt_motaloaisp =isset($_POST["txt_motaloaisp"])?$_POST["txt_motaloaisp"]:"";
        $parent_id = !empty($_POST["parent_id"]) ? $_POST["parent_id"] : null;
        $obj=$this->model("AdProductTypeModel");
        $obj->insert($txt_maloaisp, $txt_tenloaisp, $txt_motaloaisp, $parent_id);
        header("Location:".APP_URL."/ProductType/");    
        exit();
    }
    public function edit($maLoaiSP)
    {
        $obj=$this->model("AdProductTypeModel");
        $product = $obj->find("tblloaisp",$maLoaiSP);
        $productList = $obj->all("tblloaisp"); // Lấy lại toàn bộ danh sách
        $this->view("adminPage",["page"=>"ProductTypeView",
                            'productList' => $productList,
                            'editItem' => $product]);
    }
    public function update($maLoaiSP)
    {
        $tenLoaiSP = $_POST['txt_tenloaisp'];
        $moTaLoaiSP = $_POST['txt_motaloaisp'];
        $parent_id = !empty($_POST["parent_id"]) ? $_POST["parent_id"] : null;
        $obj=$this->model("AdProductTypeModel");
        $obj->update($maLoaiSP,$tenLoaiSP,$moTaLoaiSP, $parent_id);
        header("Location:".APP_URL."/ProductType/");    
        exit();
        // Quay lại trang danh sách
    }

}

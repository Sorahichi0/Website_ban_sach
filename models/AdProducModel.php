<?php
require_once "BaseModel.php";
class AdProducModel extends BaseModel{
    private $table="tblsanpham";
    public function insert($maLoaiSP,$masp,$tensp, $author, $publisher, $hinhanh,$soluong,
    $giaNhap,$giaXuat,$khuyenmai,$mota,$createDate) {
        // Kiểm tra bảng có trong danh sách không
        if (!array_key_exists($this->table, $this->primaryKeys)) {
            throw new Exception("Bảng không hợp lệ hoặc chưa được định nghĩa.");
        }
        // Kiểm tra xem mã  sản phẩm đã tồn tại chưa
        $column = $this->primaryKeys[$this->table];
        if($this->check($this->table, $column, $masp)>0){
            echo "Mã sản phẩm đã tồn tại. Vui lòng chọn mã khác.";
            return;
        }
        else{
            // Chuẩn bị câu lệnh INSERT
            $sql = "INSERT INTO tblsanpham (maLoaiSP, masp, tensp, author, publisher, hinhanh, soluong,
            giaNhap, giaXuat, khuyenmai, mota, createDate) 
                    VALUES (:maLoaiSP, :masp, :tensp, :author, :publisher, :hinhanh, :soluong, :giaNhap,
                    :giaXuat, :khuyenmai, :mota, :createDate)";
            try {
                $stmt = $this->db->prepare($sql);
                // Gán giá trị cho các tham số
                $stmt->bindParam(':maLoaiSP', $maLoaiSP);
                $stmt->bindParam(':masp', $masp);
                $stmt->bindParam(':tensp', $tensp);
                $stmt->bindParam(':author', $author);
                $stmt->bindParam(':publisher', $publisher);
                $stmt->bindParam(':hinhanh', $hinhanh);
                $stmt->bindParam(':soluong', $soluong);
                $stmt->bindParam(':giaNhap', $giaNhap);
                $stmt->bindParam(':giaXuat', $giaXuat);
                $stmt->bindParam(':khuyenmai', $khuyenmai);
                $stmt->bindParam(':mota', $mota);
                $stmt->bindParam(':createDate', $createDate);
                $stmt->execute();
                echo "Thêm sản phẩm thành công.";
            } catch (PDOException $e) {
                echo "Thất bại" . $e->getMessage();
            } 
        }    
    }
    
    public function update($maLoaiSP,$masp,$tensp, $author, $publisher, $hinhanh,$soluong,$giaNhap,
    $giaXuat,$khuyenmai,$mota,$createDate) {
        // Chuẩn bị câu lệnh UPDATE
        $sql = "UPDATE tblsanpham SET 
                maLoaiSP = :maLoaiSP,
                masp = :masp, 
                tensp = :tensp,
                author = :author,
                publisher = :publisher,
                hinhanh = :hinhanh,
                soluong = :soluong,
                giaNhap = :giaNhap,
                giaXuat = :giaXuat,
                khuyenmai = :khuyenmai,
                mota = :mota,
                createDate = :createDate
                WHERE masp = :masp";
        try {
            $stmt = $this->db->prepare($sql); 
            // Gán giá trị cho các tham số
            $stmt->bindParam(':maLoaiSP', $maLoaiSP);
            $stmt->bindParam(':masp', $masp);
            $stmt->bindParam(':tensp', $tensp);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':publisher', $publisher);
            $stmt->bindParam(':hinhanh', $hinhanh);
            $stmt->bindParam(':soluong', $soluong);
            $stmt->bindParam(':giaNhap', $giaNhap);
            $stmt->bindParam(':giaXuat', $giaXuat);
            $stmt->bindParam(':khuyenmai', $khuyenmai);
            $stmt->bindParam(':mota', $mota);
            $stmt->bindParam(':createDate', $createDate);
            // Thực thi câu lệnh
            $stmt->execute();
            //echo "Cập nhật loại sản phẩm thành công.";
        } catch (PDOException $e) {
            echo "Cập nhật không thành công: " . $e->getMessage();
        }
    }

    public function updateQuantity($masp, $quantitySold) {
        $sql = "UPDATE {$this->table} SET soluong = soluong - :quantitySold WHERE masp = :masp AND soluong >= :quantitySold";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':quantitySold' => $quantitySold, ':masp' => $masp]);
    }

    public function findByCategory($maLoaiSP) {
        $sql = "SELECT * FROM {$this->table} WHERE maLoaiSP = :maLoaiSP ORDER BY createDate DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':maLoaiSP' => $maLoaiSP]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['discounted_price'] = $product['giaXuat'] * (1 - $product['khuyenmai'] / 100);
            $product['sold_count'] = $this->getSoldCount($product['masp']);
        }
        return $products;
    }
    public function getSoldCount($productId) {
        $sql = "SELECT SUM(quantity) as sold_count FROM order_details WHERE product_id = ?";
        $result = $this->select($sql, [$productId]);
        return $result[0]['sold_count'] ?? 0;
    }

    public function all($table) {
        $products = parent::all($table);
        foreach ($products as &$product) {
            $product['discounted_price'] = $product['giaXuat'] * (1 - $product['khuyenmai'] / 100);
            $product['sold_count'] = $this->getSoldCount($product['masp']);
        }
        return $products;
    }

    public function find($table, $id) {
        $product = parent::find($table, $id);
        if ($product) {
            $product['discounted_price'] = $product['giaXuat'] * (1 - $product['khuyenmai'] / 100);
            $product['sold_count'] = $this->getSoldCount($product['masp']);
        }
        return $product;
    }
}

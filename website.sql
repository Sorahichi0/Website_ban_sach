-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 10, 2025 lúc 04:26 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `website`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `masp` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `create_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT 5,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Chờ duyệt, 1: Đã duyệt',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `user_id`, `rating`, `content`, `status`, `created_at`) VALUES
(3, 'Manga1', 25, 5, 'okokokokokok', 1, '2025-11-26 06:32:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `shipping_method` varchar(100) DEFAULT NULL,
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_email` varchar(100) DEFAULT NULL,
  `receiver` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `transaction_info` varchar(50) DEFAULT 'chothanhtoan',
  `order_status` varchar(50) NOT NULL DEFAULT 'Chờ xác nhận' COMMENT 'Trạng thái xử lý đơn hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `total_amount`, `shipping_method`, `shipping_cost`, `created_at`, `user_email`, `receiver`, `phone`, `address`, `transaction_info`, `order_status`) VALUES
(78, 25, 'HD1764128178', 95000.00, NULL, 0.00, '2025-11-26 10:36:18', 'hieuholeminh@gmail.com', 'Hiếu', '0123456789', '43 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(79, 25, 'HD1764128300', 100000.00, NULL, 0.00, '2025-11-26 10:38:20', 'hieuholeminh@gmail.com', 'Hiếu', '0123456789', '43 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(80, 25, 'HD1764129718', 118750.00, NULL, 0.00, '2025-11-26 11:01:58', 'hieuholeminh@gmail.com', 'Hiếu', '0123456789', '43 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(81, 25, 'HD1764129985', 125000.00, NULL, 0.00, '2025-11-26 11:06:25', 'hieuholeminh@gmail.com', 'Hiếu', '0123456789', '43 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(82, 25, 'HD1764138849', 50000.00, NULL, 0.00, '2025-11-26 13:34:09', 'hieuholeminh@gmail.com', 'Hiếu', '0123456789', '43 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(83, 14, 'HD1764138961', 108000.00, NULL, 0.00, '2025-11-26 13:36:01', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(84, 14, 'HD1764139182', 100000.00, NULL, 0.00, '2025-11-26 13:39:42', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(85, 14, 'HD1764139191', 125000.00, NULL, 0.00, '2025-11-26 13:39:51', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng trung hòa hà nội', 'chothanhtoan', 'Chờ xác nhận'),
(86, 22, 'HD1764141821', 100000.00, NULL, 0.00, '2025-11-26 14:23:41', 'hoan88092003@gmail.com', 'hoàn', '09782608600', 'Quang lãng phú xuyên hà nội01 sảo thượng', 'chothanhtoan', 'Chờ xác nhận'),
(87, 14, 'HD1764146469', 125000.00, NULL, 0.00, '2025-11-26 15:41:09', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'chothanhtoan', 'Chờ xác nhận'),
(88, 14, 'HD1764146509', 108000.00, NULL, 0.00, '2025-11-26 15:41:49', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'chothanhtoan', 'Chờ xác nhận'),
(89, 14, 'HD1764246763', 50000.00, NULL, 0.00, '2025-11-27 19:32:43', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'chothanhtoan', 'Chờ xác nhận'),
(90, 14, 'HD1764248843', 60000.00, NULL, 0.00, '2025-11-27 20:07:23', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'chothanhtoan', 'Chờ xác nhận'),
(91, 14, 'HD1764249313', 30000.00, NULL, 0.00, '2025-11-27 20:15:13', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'chothanhtoan', 'Chờ xác nhận'),
(92, 14, 'HD1764835561', 100000.00, NULL, 0.00, '2025-12-04 15:06:01', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Hoàn thành'),
(94, 14, 'HD1765179913', 65000.00, 'Giao hàng tiêu chuẩn', 15000.00, '2025-12-08 14:45:13', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'Chờ thanh toán', 'Chờ xác nhận'),
(95, 14, 'HD1765180176', 80000.00, 'Giao hàng nhanh', 30000.00, '2025-12-08 14:49:36', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Hoàn thành'),
(96, 14, 'HD1765182444', 77500.00, 'Giao hàng nhanh', 30000.00, '2025-12-08 15:27:24', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'Chờ thanh toán', 'Chờ xác nhận'),
(97, 14, 'HD1765183027', 63250.00, 'Giao hàng nhanh', 30000.00, '2025-12-08 15:37:07', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Hoàn thành'),
(98, 14, 'HD1765185632', 49000.00, 'Giao hàng nhanh', 30000.00, '2025-12-08 16:20:32', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Chờ xác nhận'),
(99, 14, 'HD1765186449', 49000.00, 'Giao hàng nhanh', 30000.00, '2025-12-08 16:34:09', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Chờ xác nhận'),
(100, 14, 'HD1765186797', 49000.00, 'Giao hàng nhanh', 30000.00, '2025-12-08 16:39:57', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Chờ xác nhận'),
(101, 14, 'HD1765187077', 49000.00, 'Giao hàng nhanh', 30000.00, '2025-12-08 16:44:37', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Chờ xác nhận'),
(102, 14, 'HD1765311979', 48500.00, 'Giao hàng nhanh', 20000.00, '2025-12-10 03:26:19', 'hoanganhtinhtien@gmail.com', 'Trần Triêu', '0123456789', '34 trần duy hưng', 'dathanhtoan', 'Chờ xác nhận');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `product_id` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `sale_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `sale_price`, `total`, `image`, `product_type`, `product_name`) VALUES
(87, '78', 'Manga1', 1, 50000.00, 50000.00, 50000.00, '1.jpg', '', 'Doraemon 1'),
(88, '78', 'Manga2', 1, 50000.00, 50000.00, 50000.00, '2.jpg', '', 'Doraemon 2'),
(89, '79', 'lapTrinh1', 1, 100000.00, 100000.00, 100000.00, '300-bai-code-thieu-nhi.jpg', '', '300 bài code thiếu nhi'),
(90, '80', 'lapTrinh2', 1, 125000.00, 125000.00, 125000.00, '20250917_SBpNRhffQ1.jpg', '', 'C Programming: A Modern Approach, 2nd Edition by K. N. King'),
(91, '81', 'lapTrinh2', 1, 125000.00, 125000.00, 125000.00, '20250917_SBpNRhffQ1.jpg', '', 'C Programming: A Modern Approach, 2nd Edition by K. N. King'),
(92, '82', 'Manga1', 1, 50000.00, 50000.00, 50000.00, '1.jpg', '', 'Doraemon 1'),
(93, '83', 'lapTrinh3', 1, 120000.00, 108000.00, 108000.00, 'code1.jpg', '', 'Code Dạo Kí Sự - Lập Trình Viên Đâu Phải Chỉ Biết Code'),
(94, '84', 'lapTrinh1', 1, 100000.00, 100000.00, 100000.00, '300-bai-code-thieu-nhi.jpg', '', '300 bài code thiếu nhi'),
(95, '85', 'lapTrinh2', 1, 125000.00, 125000.00, 125000.00, '20250917_SBpNRhffQ1.jpg', '', 'C Programming: A Modern Approach, 2nd Edition by K. N. King'),
(96, '86', 'lapTrinh1', 1, 100000.00, 100000.00, 100000.00, '300-bai-code-thieu-nhi.jpg', '', '300 bài code thiếu nhi'),
(97, '87', 'lapTrinh2', 1, 125000.00, 125000.00, 125000.00, '20250917_SBpNRhffQ1.jpg', '', 'C Programming: A Modern Approach, 2nd Edition by K. N. King'),
(98, '88', 'lapTrinh3', 1, 120000.00, 108000.00, 108000.00, 'code1.jpg', '', 'Code Dạo Kí Sự - Lập Trình Viên Đâu Phải Chỉ Biết Code'),
(99, '89', 'Manga1', 1, 50000.00, 50000.00, 50000.00, '1.jpg', '', 'Doraemon 1'),
(100, '90', 'manhwa2', 2, 30000.00, 30000.00, 60000.00, 'tro-thanh-dau-bep-rieng-cua-nhan-vat-phan-dien.jpg', '', 'Trở Thành Đầu Bếp Riêng Của Nhân Vật Phản Diện'),
(101, '91', 'manhwa2', 1, 30000.00, 30000.00, 30000.00, 'tro-thanh-dau-bep-rieng-cua-nhan-vat-phan-dien.jpg', '', 'Trở Thành Đầu Bếp Riêng Của Nhân Vật Phản Diện'),
(102, '92', 'lapTrinh1', 1, 100000.00, 100000.00, 100000.00, '300-bai-code-thieu-nhi.jpg', '', '300 bài code thiếu nhi'),
(103, '93', 'Manga1', 1, 50000.00, 50000.00, 50000.00, '1.jpg', '', 'Doraemon 1'),
(104, '94', 'Manga1', 1, 50000.00, 50000.00, 50000.00, '1.jpg', '', 'Doraemon 1'),
(105, '95', 'Manga2', 1, 50000.00, 50000.00, 50000.00, '2.jpg', '', 'Doraemon 2'),
(106, '96', 'Manga2', 1, 50000.00, 50000.00, 50000.00, '2.jpg', '', 'Doraemon 2'),
(107, '97', 'Manga3', 1, 35000.00, 35000.00, 35000.00, 'manga1.jpg', '', 'One Piece'),
(108, '98', 'nauan2', 1, 20000.00, 20000.00, 20000.00, 'nauan2.jpg', '', 'Miếng Ngon Hà Nội'),
(109, '99', 'nauan2', 1, 20000.00, 20000.00, 20000.00, 'nauan2.jpg', '', 'Miếng Ngon Hà Nội'),
(110, '100', 'nauan2', 1, 20000.00, 20000.00, 20000.00, 'nauan2.jpg', '', 'Miếng Ngon Hà Nội'),
(111, '101', 'nauan2', 1, 20000.00, 20000.00, 20000.00, 'nauan2.jpg', '', 'Miếng Ngon Hà Nội'),
(112, '102', 'manhwa1', 1, 30000.00, 30000.00, 30000.00, 'mac-du-thich.jpg', '', 'Mặc Dù Thích Ở Nhà Nhưng Tôi Lại Xuyên Vào Thể Loại Giam Cầm Đen Tối');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Hiển thị'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image`, `created_at`, `status`) VALUES
(10, 'Voucher cho người dùng lần đầu', 'Người dùng lần đầu mua 1 đơn hàng khi dùng voucher này sẽ có ưu đãi giảm giá tốt. Dùng ngay', '69266af629276-pngtree-voucher-discount-png-image_4613299.png', '2025-11-26 02:50:30', '1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `description`) VALUES
(1, 'SuperAdmin', 'Quản trị viên cao nhất, có toàn quyền hệ thống.'),
(2, 'Admin', 'Quản trị viên, có quyền quản lý sản phẩm, đơn hàng, bài viết.'),
(3, 'Customer', 'Khách hàng mua sắm trên trang web.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `description`, `cost`, `is_active`) VALUES
(1, 'Giao hàng tiêu chuẩn', 'Dự kiến giao trong 3-5 ngày làm việc', 10000.00, 1),
(2, 'Giao hàng nhanh', 'Dự kiến giao trong 1-2 ngày làm việc', 20000.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblloaisp`
--

CREATE TABLE `tblloaisp` (
  `maLoaiSP` varchar(20) NOT NULL,
  `tenLoaiSP` varchar(50) NOT NULL,
  `moTaLoaiSP` varchar(200) NOT NULL,
  `parent_id` varchar(50) DEFAULT NULL COMMENT 'Mã loại SP cha'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblloaisp`
--

INSERT INTO `tblloaisp` (`maLoaiSP`, `tenLoaiSP`, `moTaLoaiSP`, `parent_id`) VALUES
('Sach1', 'Truyện', 'Truyện tranh', NULL),
('Sach10', 'Tiếng Trung', '', 'Sach6'),
('Sach11', 'Sách phong cách sống', 'life style', NULL),
('Sach12', 'Ẩm thực & Nấu ăn', 'Food', 'Sach11'),
('Sach13', 'Du lịch & Trải nghiệm', 'travel', 'Sach11'),
('Sach14', 'Truyện Việt Nam', 'các thể loại truyện của Việt Nam', 'Sach1'),
('Sach16', 'Tâm lý & Kỹ năng sống', 'như tên', NULL),
('Sach17', 'Phát triển bản thân', 'self help', 'Sach16'),
('Sach18', 'Tâm lý học Ứng Dụng', 'như tên', 'Sach16'),
('Sach2', 'Sách chuyên ngành', 'chuyên 1 lĩnh vực', NULL),
('Sach3', 'Manga', 'truyện tranh nhật bản', 'Sach1'),
('Sach4', 'Công nghệ thông tin & Lập trình', 'cntt', 'Sach2'),
('Sach5', 'Manhwa', 'truyện tranh hàn quốc', 'Sach1'),
('Sach6', 'Sách học ngoại ngữ', 'Các sách học ngoại ngữ', NULL),
('Sach7', 'Tiếng Anh', 'tiếng anh', 'Sach6'),
('Sach8', 'Kinh tế & Quản trị', 'như tên', NULL),
('Sach9', 'Marketing & Bán hàng', '', 'Sach8');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblsanpham`
--

CREATE TABLE `tblsanpham` (
  `maLoaiSP` varchar(20) NOT NULL,
  `masp` varchar(20) NOT NULL,
  `tensp` varchar(255) NOT NULL,
  `author` varchar(255) DEFAULT NULL COMMENT 'Tên tác giả',
  `publisher` varchar(255) DEFAULT NULL COMMENT 'Nhà xuất bản',
  `hinhanh` varchar(50) NOT NULL,
  `soluong` int(11) NOT NULL,
  `giaNhap` int(11) NOT NULL,
  `giaXuat` int(11) NOT NULL,
  `khuyenmai` int(11) NOT NULL,
  `mota` mediumtext NOT NULL,
  `createDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblsanpham`
--

INSERT INTO `tblsanpham` (`maLoaiSP`, `masp`, `tensp`, `author`, `publisher`, `hinhanh`, `soluong`, `giaNhap`, `giaXuat`, `khuyenmai`, `mota`, `createDate`) VALUES
('Sach4', 'lapTrinh1', '300 bài code thiếu nhi', 'Đang cập nhật', 'Đang cập nhật', '300-bai-code-thieu-nhi.jpg', 27, 75000, 100000, 0, '300 code', '2025-11-11'),
('Sach4', 'lapTrinh2', 'C Programming: A Modern Approach, 2nd Edition by K. N. King', 'Đang cập nhật', 'Đang cập nhật', '20250917_SBpNRhffQ1.jpg', 5, 100000, 125000, 0, 'Sách dạy lập trình ngôn ngữ C', '2025-11-11'),
('Sach4', 'lapTrinh3', 'Code Dạo Kí Sự - Lập Trình Viên Đâu Phải Chỉ Biết Code', 'Phạm Huy Hoàng', 'NXB Dân Trí', 'code1.jpg', 18, 100000, 120000, 10, 'Sách tập trung vào khả năng tự học và định hướng người đọc. Có kĩ năng tự học, có định hướng tốt, bạn sẽ dễ dàng sống sót và thăng tiến trong ngành này.', '2025-11-25'),
('Sach3', 'Manga1', 'Doraemon 1', 'Fujiko F. Fujio', 'Kim đồng', '1.jpg', 43, 30000, 50000, 0, 'Tập 1', '2025-11-11'),
('Sach3', 'Manga2', 'Doraemon 2', 'Fujiko F. Fujio', 'Kim Đồng', '2.jpg', 46, 30000, 50000, 0, 'tập 2', '2025-11-11'),
('Sach3', 'Manga3', 'One Piece', 'Eiichiro Oda', 'Kim Đồng', 'manga1.jpg', 48, 30000, 35000, 0, 'Tập 1', '2025-11-25'),
('Sach5', 'manhwa1', 'Mặc Dù Thích Ở Nhà Nhưng Tôi Lại Xuyên Vào Thể Loại Giam Cầm Đen Tối', 'Đang cập nhật', 'Đang cập nhật', 'mac-du-thich.jpg', 29, 15000, 30000, 0, 'Mặc Dù Thích Ở Nhà Nhưng Tôi Lại Xuyên Vào Thể Loại Giam Cầm Đen Tối', '2025-11-11'),
('Sach5', 'manhwa2', 'Trở Thành Đầu Bếp Riêng Của Nhân Vật Phản Diện', 'Đang cập nhật', 'Đang cập nhật', 'tro-thanh-dau-bep-rieng-cua-nhan-vat-phan-dien.jpg', 57, 15000, 30000, 0, 'Trở Thành Đầu Bếp Riêng Của Nhân Vật Phản Diện', '2025-11-12'),
('Sach9', 'marketing1', 'Sách Nguyên Lý Marketing', 'Philip Kotler, Gary Armstrong', 'NXB Đại Học Kinh Tế Quốc Dân', 'marketing.jpg', 15, 500000, 750000, 10, 'giúp bạn hiểu về marketing', '2025-11-25'),
('Sach9', 'marketing2', 'Marketing Giỏi Phải Kiếm Được Tiền', 'SERGIO ZYMAN', 'Nhà Xuất Bản Thế Giới', 'marketing1.jpg', 20, 250000, 300000, 0, 'hiện chưa có mô tả', '2025-11-25'),
('Sach12', 'nauan1', '80 Ngày Ăn Khắp Thế Giới', 'Phan Anh', 'NXB Kim Đồng', 'nauan1.jpg', 15, 20000, 30000, 0, 'Tái Bản 2019', '2025-11-25'),
('Sach12', 'nauan2', 'Miếng Ngon Hà Nội', 'Vũ Bằng', 'NXB Hội Nhà Văn', 'nauan2.jpg', 16, 15000, 20000, 0, 'hẹ hẹ', '2025-11-25'),
('Sach17', 'ptbt1', 'Đắc Nhân Tâm', 'Dale Carnegie', 'NXB Tổng Hợp TPHCM', 'ptbt1.jpg', 46, 90000, 100000, 5, 'Tái Bản 2021', '2025-11-25'),
('Sach17', 'ptbt2', 'NHÀ GIẢ KIM', 'paulo coelho', ' Hội Nhà Văn', 'ptbt2.jpg', 21, 70000, 80000, 10, 'hiện chưa có mô tả', '2025-11-25'),
('Sach7', 'Ta1', 'Hack não 1500', 'Nguyễn Văn Hiệp', 'Đang cập nhật', 'Ta_hacknao.jpg', 30, 300000, 350000, 5, 'dạy tiếng anh bao hiệu quả', '2025-11-25'),
('Sach7', 'Ta2', 'Cambridge Grammar for IELTS', 'Diana Hopkins, Pauline Cullen', 'ĐH Cambridge', 'Ta_IELTS.jpg', 75, 80000, 100000, 10, 'học IELTS', '2025-11-25'),
('Sach10', 'TiengTrung1', 'Giáo trình Hán Ngữ 1 - Tập 1', 'Dương Ký Châu (Chủ biên)', 'NXB Đại học Quốc gia Hà Nội', 'Ttrung_giaotrinhhanngu_1_tap_1.jpg', 25, 140000, 150000, 5, 'Cuốn sách được chia thành các phần cơ bản và dễ tiếp cận', '2025-11-25'),
('Sach10', 'TiengTrung2', 'Tập viết chữ Hán theo giáo trình hán ngữ phiên bản mới', 'Đang cập nhật', 'Đang cập nhật', 'Ttrung_tap_viet_chu_han.jpg', 30, 50000, 60000, 5, 'làm quen và nắm vững chữ Hán', '2025-11-25'),
('Sach18', 'tl1', 'Tư Duy Nhanh Và Chậm', 'Daniel Kahneman', 'Thế Giới', 'tl1.jpg', 56, 400000, 450000, 10, 'hiện chưa có mô tả', '2025-11-25'),
('Sach18', 'tl2', 'Phi Lý Trí', 'Dan Ariely', 'NXB Lao Động', 'tl2.jpg', 31, 312000, 400000, 50, 'hiện chưa có mô tả', '2025-11-25'),
('Sach13', 'traiNghiem1', 'Tôi Là Một Con Lừa', 'Nguyễn Phương Mai', 'Hội Nhà Văn', 'pcs.jpg', 28, 30000, 35000, 0, 'hiện chưa có mô tả', '2025-11-25'),
('Sach13', 'traiNghiem2', 'Xách Ba Lô Lên Và Đi', 'Huyền Chip', 'NXB Hội Nhà văn', 'pcs1.jpg', 40, 40000, 50000, 5, 'hiện chưa có mô tả', '2025-11-25'),
('Sach14', 'Tvn1', 'Long Thần Tướng', 'Nguyễn Khánh Dương, Nguyễn Thành Phong', 'Comicola', 'T_VN1.jpg', 20, 15000, 20000, 0, 'Tập 1', '2025-11-25'),
('Sach14', 'Tvn2', 'Thần Đồng Đất Việt', 'Nhiều tác giả', 'NXB Văn Hóa Sài Gòn', 'T_VN2.jpg', 15, 20000, 25000, 0, 'Tập 1', '2025-11-25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_token` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = Active, 0 = Locked',
  `role_id` int(11) DEFAULT 3,
  `phone` varchar(20) DEFAULT '',
  `address` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `fullname`, `email`, `password`, `is_verified`, `verification_token`, `created_at`, `role`, `is_active`, `role_id`, `phone`, `address`) VALUES
(14, 'Trần Triêu', 'hoanganhtinhtien@gmail.com', '$2y$10$2VKgq19X0xuwhxV58vJyE.uyFP1E8Sx5edkcQkBYO9Xw6eIt75VYe', 0, 956613, '2025-10-01 20:58:58', 'user', 1, 3, '0123456789', '34 trần duy hưng'),
(22, 'hoàn', 'hoan88092003@gmail.com', '$2y$10$JyEeUHqH3u7/W4.E6NJibuN0bXHLNNs1TupfFQAzXwE/By6UqI0Xq', 0, 0, '2025-10-22 22:31:01', 'user', 1, 3, '09782608600', 'Quang lãng phú xuyên hà nội\r\n01 sảo thượng'),
(25, 'Hiếu', 'hieuholeminh@gmail.com', '$2y$10$qq0jJdP7jIKfpUG5I3hZz.8PALFhi0rz3zxur/9Yl05XZc7YwDgsW', 0, 0, '2025-10-22 22:45:19', 'user', 1, 3, '0123456789', '43 trần duy hưng trung hòa hà nội'),
(26, 'admin', 'admin@gmail.com', '$2y$10$q4pTr3927fZRevlW32rE.et4FgZGG08B84y/hwCBmnfwS4sZoZAJi', 0, 0, '2025-12-04 19:16:42', 'user', 1, 1, '', ''),
(27, 'admin1', 'admin1@gmail.com', '$2y$10$W62VRbZ7EagM3MMTbuSfk.nHThY.MwA7iWxjp1ySHK0mqu9Nwcc8K', 0, 0, '2025-12-04 19:17:12', 'user', 1, 2, '', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_promo_codes`
--

CREATE TABLE `tbl_promo_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percentage','fixed','free_shipping') NOT NULL DEFAULT 'percentage' COMMENT 'fixed: Giảm tiền, percentage: Giảm %, free_shipping: Miễn phí vận chuyển',
  `value` decimal(15,2) NOT NULL COMMENT 'Giá trị giảm',
  `min_order_value` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Giá trị đơn hàng tối thiểu',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `usage_limit` int(11) NOT NULL DEFAULT 0 COMMENT 'Giới hạn số lần sử dụng (0=không giới hạn)',
  `usage_count` int(11) NOT NULL DEFAULT 0 COMMENT 'Số lần đã sử dụng',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=active, 0=inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_promo_codes`
--

INSERT INTO `tbl_promo_codes` (`id`, `code`, `type`, `value`, `min_order_value`, `start_date`, `end_date`, `usage_limit`, `usage_count`, `status`, `created_at`) VALUES
(1, 'NGAY25THANG10', 'percentage', 10.00, 0.00, '2025-10-24 19:10:00', '2025-10-30 19:10:00', 0, 0, 1, '2025-10-25 12:10:21'),
(2, 'DAY', 'fixed', 1000000.00, 0.00, '2025-10-24 19:23:00', '2025-10-27 00:00:00', 0, 0, 1, '2025-10-25 12:24:25'),
(3, '5-11', 'percentage', 10.00, 0.00, '2025-11-04 00:00:00', '2025-11-06 00:00:00', 0, 2, 1, '2025-11-05 11:06:54'),
(4, 'N5/11', 'percentage', 5.00, 0.00, '2025-11-04 00:00:00', '2025-11-06 00:00:00', 1, 0, 1, '2025-11-05 11:52:09'),
(5, 'NEW_USER', 'percentage', 20.00, 0.00, '2025-11-01 12:00:00', '2026-12-31 12:00:00', 1, 0, 1, '2025-11-25 06:54:01'),
(6, 'HEHE', 'percentage', 5.00, 0.00, '2025-01-25 13:54:00', '2026-12-25 13:54:00', 0, 5, 1, '2025-11-25 06:55:07'),
(7, 'FREESHIP', 'free_shipping', 100.00, 0.00, '2025-12-01 00:00:00', '2026-01-31 23:59:00', 100, 0, 1, '2025-12-08 07:23:25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_suppliers`
--

CREATE TABLE `tbl_suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contract_info` text DEFAULT NULL COMMENT 'Thông tin hợp đồng hoặc ghi chú',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_suppliers`
--

INSERT INTO `tbl_suppliers` (`id`, `name`, `contact_person`, `email`, `phone`, `address`, `contract_info`, `created_at`) VALUES
(1, 'kim đồng', 'Trần văn A', 'A123@gmail.com', '0987456321', 'hà nội', 'sách truyện doraemon', '2025-12-04 08:51:28');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product_unique` (`user_id`,`masp`),
  ADD KEY `fk_cart_product` (`masp`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_rating` (`rating`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Chỉ mục cho bảng `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblloaisp`
--
ALTER TABLE `tblloaisp`
  ADD PRIMARY KEY (`maLoaiSP`),
  ADD KEY `FK_loaisp_parent` (`parent_id`);

--
-- Chỉ mục cho bảng `tblsanpham`
--
ALTER TABLE `tblsanpham`
  ADD PRIMARY KEY (`masp`),
  ADD KEY `idx_tensp` (`tensp`),
  ADD KEY `idx_author` (`author`),
  ADD KEY `idx_publisher` (`publisher`),
  ADD KEY `idx_giaXuat` (`giaXuat`),
  ADD KEY `idx_maLoaiSP` (`maLoaiSP`);

--
-- Chỉ mục cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_role` (`role_id`);

--
-- Chỉ mục cho bảng `tbl_promo_codes`
--
ALTER TABLE `tbl_promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `tbl_promo_codes`
--
ALTER TABLE `tbl_promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tbl_suppliers`
--
ALTER TABLE `tbl_suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`masp`) REFERENCES `tblsanpham` (`masp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tblloaisp`
--
ALTER TABLE `tblloaisp`
  ADD CONSTRAINT `FK_loaisp_parent` FOREIGN KEY (`parent_id`) REFERENCES `tblloaisp` (`maLoaiSP`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `tbluser`
--
ALTER TABLE `tbluser`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

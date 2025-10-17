CREATE DATABASE IF NOT EXISTS `toko_kopi` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `toko_kopi`;

-- Tabel barang
CREATE TABLE IF NOT EXISTS `barang` (
  `id_barang` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_barang` VARCHAR(100) NOT NULL,
  `harga` DECIMAL(12,2) NOT NULL CHECK (harga >= 0),
  `stok` INT NOT NULL DEFAULT 0 CHECK (stok >= 0)
) ENGINE=InnoDB;

-- Tabel pembeli
CREATE TABLE IF NOT EXISTS `pembeli` (
  `id_pembeli` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_pembeli` VARCHAR(150) NOT NULL,
  `alamat` TEXT
) ENGINE=InnoDB;

-- Tabel transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` INT AUTO_INCREMENT PRIMARY KEY,
  `id_pembeli` INT NOT NULL,
  `id_barang` INT NOT NULL,
  `jumlah` INT NOT NULL CHECK (jumlah > 0),
  `total_harga` DECIMAL(14,2) NOT NULL,
  `tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_pembeli) REFERENCES pembeli(id_pembeli) ON DELETE CASCADE,
  FOREIGN KEY (id_barang) REFERENCES barang(id_barang) ON DELETE CASCADE
) ENGINE=InnoDB;

DELIMITER $$
CREATE TRIGGER barang_before_insert BEFORE INSERT ON barang
FOR EACH ROW
BEGIN
  SET NEW.nama_barang = UPPER(NEW.nama_barang);
END$$

CREATE TRIGGER barang_before_update BEFORE UPDATE ON barang
FOR EACH ROW
BEGIN
  SET NEW.nama_barang = UPPER(NEW.nama_barang);
END$$

CREATE TRIGGER transaksi_before_insert BEFORE INSERT ON transaksi
FOR EACH ROW
BEGIN
  DECLARE v_harga DECIMAL(12,2);
  DECLARE v_stok INT;
  SELECT harga, stok INTO v_harga, v_stok FROM barang WHERE id_barang = NEW.id_barang FOR UPDATE;
  IF v_stok < NEW.jumlah THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok tidak mencukupi.';
  END IF;
  SET NEW.total_harga = v_harga * NEW.jumlah;
  UPDATE barang SET stok = stok - NEW.jumlah WHERE id_barang = NEW.id_barang;
END$$

CREATE TRIGGER transaksi_after_delete AFTER DELETE ON transaksi
FOR EACH ROW
BEGIN
  UPDATE barang SET stok = stok + OLD.jumlah WHERE id_barang = OLD.id_barang;
END$$

CREATE TRIGGER transaksi_before_update BEFORE UPDATE ON transaksi
FOR EACH ROW
BEGIN
  DECLARE v_harga DECIMAL(12,2);
  DECLARE v_stok INT;
  SELECT harga, stok INTO v_harga, v_stok FROM barang WHERE id_barang = NEW.id_barang FOR UPDATE;
  IF OLD.id_barang <> NEW.id_barang THEN
    UPDATE barang SET stok = stok + OLD.jumlah WHERE id_barang = OLD.id_barang;
    SELECT stok INTO v_stok FROM barang WHERE id_barang = NEW.id_barang;
    IF v_stok < NEW.jumlah THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok tidak mencukupi untuk barang baru.';
    END IF;
    UPDATE barang SET stok = stok - NEW.jumlah WHERE id_barang = NEW.id_barang;
  ELSE
    SET @diff = NEW.jumlah - OLD.jumlah;
    IF @diff > 0 THEN
      IF v_stok < @diff THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stok tidak mencukupi untuk penambahan jumlah.';
      END IF;
      UPDATE barang SET stok = stok - @diff WHERE id_barang = NEW.id_barang;
    ELSEIF @diff < 0 THEN
      UPDATE barang SET stok = stok + (OLD.jumlah - NEW.jumlah) WHERE id_barang = NEW.id_barang;
    END IF;
  END IF;
  SET NEW.total_harga = v_harga * NEW.jumlah;
END$$
DELIMITER ;

-- Sample data
INSERT INTO barang (nama_barang, harga, stok) VALUES
('ESPRESSO', 12000.00, 30),
('LATTE', 18000.00, 20),
('CAPPUCCINO', 20000.00, 15);

INSERT INTO pembeli (nama_pembeli, alamat) VALUES
('BUDI SUKARNO','Jl. Merdeka No.1'),
('SITI AMINAH','Jl. Sudirman 12');

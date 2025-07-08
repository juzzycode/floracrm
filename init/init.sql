-- Florist CRM Database Schema
-- Drop existing tables if they exist
DROP TABLE IF EXISTS inventory_pricing;
DROP TABLE IF EXISTS vendor_discount_groups;
DROP TABLE IF EXISTS discount_groups;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS vendors;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS companies;

-- Companies table (multi-tenant support)
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'USA',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users table (multiple users per company)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    role ENUM('admin', 'manager', 'user') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Vendors table
CREATE TABLE vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255),
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    zip_code VARCHAR(20),
    country VARCHAR(100) DEFAULT 'USA',
    payment_terms VARCHAR(255),
    delivery_days INT DEFAULT 7,
    minimum_order DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Inventory/Products table
CREATE TABLE inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    sku VARCHAR(100) NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    unit VARCHAR(50) DEFAULT 'piece',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    UNIQUE KEY unique_sku_company (sku, company_id)
);

-- Discount groups table
CREATE TABLE discount_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Vendor discount groups (many-to-many relationship)
CREATE TABLE vendor_discount_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    discount_group_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE CASCADE,
    FOREIGN KEY (discount_group_id) REFERENCES discount_groups(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vendor_discount (vendor_id, discount_group_id)
);

-- Inventory pricing table (many-to-many with pricing info)
CREATE TABLE inventory_pricing (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT NOT NULL,
    inventory_id INT NOT NULL,
    base_price DECIMAL(10,2) NOT NULL,
    quantity_break_1 INT DEFAULT 0,
    price_break_1 DECIMAL(10,2) DEFAULT 0.00,
    quantity_break_2 INT DEFAULT 0,
    price_break_2 DECIMAL(10,2) DEFAULT 0.00,
    quantity_break_3 INT DEFAULT 0,
    price_break_3 DECIMAL(10,2) DEFAULT 0.00,
    lead_time_days INT DEFAULT 7,
    minimum_quantity INT DEFAULT 1,
    status ENUM('active', 'inactive') DEFAULT 'active',
    effective_date DATE,
    expiry_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id) ON DELETE CASCADE,
    FOREIGN KEY (inventory_id) REFERENCES inventory(id) ON DELETE CASCADE,
    UNIQUE KEY unique_vendor_inventory (vendor_id, inventory_id)
);

-- Indexes for better performance
CREATE INDEX idx_users_company ON users(company_id);
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_vendors_company ON vendors(company_id);
CREATE INDEX idx_vendors_status ON vendors(status);
CREATE INDEX idx_inventory_company ON inventory(company_id);
CREATE INDEX idx_inventory_sku ON inventory(sku);
CREATE INDEX idx_inventory_name ON inventory(name);
CREATE INDEX idx_inventory_category ON inventory(category);
CREATE INDEX idx_pricing_vendor ON inventory_pricing(vendor_id);
CREATE INDEX idx_pricing_inventory ON inventory_pricing(inventory_id);
CREATE INDEX idx_pricing_status ON inventory_pricing(status);

-- Sample data
INSERT INTO companies (name, email, phone, address, city, state, zip_code) VALUES
('Bloom & Blossom Florists', 'info@bloomandblossom.com', '555-0101', '123 Flower St', 'New York', 'NY', '10001'),
('Garden Paradise', 'contact@gardenparadise.com', '555-0102', '456 Rose Ave', 'Los Angeles', 'CA', '90001');

INSERT INTO users (company_id, username, email, password, first_name, last_name, role) VALUES
(1, 'admin1', 'admin@bloomandblossom.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John', 'Smith', 'admin'),
(1, 'manager1', 'manager@bloomandblossom.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane', 'Doe', 'manager'),
(2, 'admin2', 'admin@gardenparadise.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bob', 'Johnson', 'admin');

INSERT INTO vendors (company_id, name, contact_person, email, phone, payment_terms, delivery_days, minimum_order) VALUES
(1, 'Fresh Flowers Supply Co.', 'Mike Wilson', 'mike@freshflowers.com', '555-1001', 'Net 30', 3, 100.00),
(1, 'Wholesale Blooms Inc.', 'Sarah Davis', 'sarah@wholesaleblooms.com', '555-1002', 'Net 15', 5, 250.00),
(1, 'Garden Direct Suppliers', 'Tom Brown', 'tom@gardendirect.com', '555-1003', 'Net 30', 7, 150.00);

INSERT INTO inventory (company_id, sku, name, description, category, unit) VALUES
(1, 'ROSE-RED-001', 'Red Roses', 'Fresh red roses, premium quality', 'Roses', 'stem'),
(1, 'LILY-WHT-001', 'White Lilies', 'Pure white lilies, elegant', 'Lilies', 'stem'),
(1, 'CARN-PNK-001', 'Pink Carnations', 'Soft pink carnations', 'Carnations', 'stem'),
(1, 'BABY-BRTH-001', 'Baby\'s Breath', 'Delicate baby\'s breath', 'Fillers', 'bunch'),
(1, 'EUCL-GRN-001', 'Eucalyptus', 'Fresh eucalyptus greenery', 'Greenery', 'bunch');

INSERT INTO discount_groups (company_id, name, description, discount_percentage) VALUES
(1, 'Premium Customer', 'Top tier customers with highest volume', 15.00),
(1, 'Standard Customer', 'Regular customers with good volume', 10.00),
(1, 'New Customer', 'New customers promotional discount', 5.00);

INSERT INTO vendor_discount_groups (vendor_id, discount_group_id) VALUES
(1, 1), (1, 2), (2, 1), (2, 2), (2, 3), (3, 2), (3, 3);

INSERT INTO inventory_pricing (vendor_id, inventory_id, base_price, quantity_break_1, price_break_1, quantity_break_2, price_break_2, lead_time_days, minimum_quantity) VALUES
(1, 1, 2.50, 50, 2.25, 100, 2.00, 2, 12),
(1, 2, 3.00, 25, 2.75, 50, 2.50, 3, 6),
(1, 3, 1.50, 100, 1.25, 200, 1.00, 2, 24),
(2, 1, 2.75, 50, 2.50, 100, 2.25, 4, 12),
(2, 2, 3.25, 25, 3.00, 50, 2.75, 5, 6),
(2, 4, 4.00, 10, 3.50, 25, 3.00, 4, 5),
(3, 1, 2.60, 50, 2.35, 100, 2.10, 6, 12),
(3, 3, 1.60, 100, 1.35, 200, 1.10, 5, 24),
(3, 5, 5.00, 10, 4.50, 25, 4.00, 7, 3);

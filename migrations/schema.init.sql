CREATE TABLE IF NOT EXISTS rk_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE COMMENT 'UUID товара',
    category_id INT NOT NULL COMMENT 'ID категории товара',
    is_active TINYINT(1) DEFAULT 1 NOT NULL COMMENT 'Флаг активности',
    name VARCHAR(100) NOT NULL COMMENT 'Тип услуги',
    description TEXT NULL COMMENT 'Описание товара',
    thumbnail VARCHAR(255) NULL COMMENT 'Ссылка на картинку',
    price DECIMAL(10,2) NOT NULL COMMENT 'Цена',
    FOREIGN KEY (category_id) REFERENCES rk_categories(id)
) COMMENT 'Товары';

CREATE INDEX idx_rk_products_is_active ON rk_products (is_active, category_id);

CREATE TABLE IF NOT EXISTS rk_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT NULL
) COMMENT 'Категории товаров';

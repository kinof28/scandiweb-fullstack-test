CREATE TABLE IF NOT EXISTS `categories` (
    `name` VARCHAR(150) NOT NULL,
    PRIMARY KEY (`name`)
);

CREATE TABLE IF NOT EXISTS `currencies` (
    `label`  VARCHAR(10) NOT NULL,
    `symbol` VARCHAR(5)  NOT NULL,
    PRIMARY KEY (`label`)
);

CREATE TABLE IF NOT EXISTS `products` (
    `id`           VARCHAR(100)  NOT NULL,
    `type` VARCHAR(50)   NOT NULL,
    `name`         VARCHAR(255)  NOT NULL,
    `brand`        VARCHAR(255)  NULL,
    `description`  LONGTEXT      NULL,
    `in_stock`     TINYINT(1)    NOT NULL DEFAULT 1,
    `gallery`      JSON          NOT NULL,
    `category`     VARCHAR(150)  NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `prices` (
    `id`             INT            NOT NULL AUTO_INCREMENT,
    `product_id`     VARCHAR(100)   NOT NULL,
    `currency_label` VARCHAR(10)    NOT NULL,
    `amount`         DECIMAL(10, 2) NOT NULL,

    PRIMARY KEY (`id`),
    CONSTRAINT `fk_prices_product`
        FOREIGN KEY (`product_id`)
        REFERENCES `products` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `fk_prices_currency`
        FOREIGN KEY (`currency_label`)
        REFERENCES `currencies` (`label`)
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS `attribute_sets` (
    `database_id` INT            NOT NULL AUTO_INCREMENT,
    `id`         VARCHAR(100) NOT NULL,
    `product_id` VARCHAR(100) NOT NULL,
    `name`       VARCHAR(150) NOT NULL,
    `type`       VARCHAR(50)  NOT NULL,

    PRIMARY KEY (`database_id`),
    CONSTRAINT `fk_attribute_sets_product`
        FOREIGN KEY (`product_id`)
        REFERENCES `products` (`id`)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `attributes` (
    `database_id` INT            NOT NULL AUTO_INCREMENT,
    `id`               VARCHAR(100) NOT NULL,
    `attribute_set_id` INT NOT NULL,
    `value`            VARCHAR(255) NOT NULL,
    `display_value`    VARCHAR(255) NOT NULL,

    PRIMARY KEY (`database_id`),
    CONSTRAINT `fk_attributes_attribute_set`
        FOREIGN KEY (`attribute_set_id`)
        REFERENCES `attribute_sets` (`database_id`)
        ON DELETE CASCADE
);

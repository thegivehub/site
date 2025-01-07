CREATE TABLE newsletter_subscribers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    status ENUM('subscribed', 'unsubscribed', 'bounced', 'spam') DEFAULT 'subscribed',
    source VARCHAR(100),
    ip_address VARCHAR(45),  -- Supports IPv6 addresses
    verification_token VARCHAR(100),
    is_verified BOOLEAN DEFAULT FALSE,
    subscribe_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribe_date TIMESTAMP NULL,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Tracking table for email campaigns
CREATE TABLE newsletter_campaigns (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    content TEXT,
    sent_date TIMESTAMP NULL,
    total_recipients INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: Email tracking table
CREATE TABLE newsletter_tracking (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subscriber_id BIGINT UNSIGNED NOT NULL,
    campaign_id BIGINT UNSIGNED NOT NULL,
    opened BOOLEAN DEFAULT FALSE,
    opened_at TIMESTAMP NULL,
    clicked BOOLEAN DEFAULT FALSE,
    clicked_at TIMESTAMP NULL,
    FOREIGN KEY (subscriber_id) REFERENCES newsletter_subscribers(id),
    FOREIGN KEY (campaign_id) REFERENCES newsletter_campaigns(id),
    INDEX idx_subscriber_campaign (subscriber_id, campaign_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================
-- PROFIT SAFAR : COMPLETE DATABASE SCHEMA
-- =========================================
-- Database: profit_safar
-- Engine  : InnoDB
-- Charset : utf8mb4
-- =========================================

CREATE DATABASE IF NOT EXISTS profit_safar;
USE profit_safar;

-- =========================================
-- 1. USERS & AUTHENTICATION
-- =========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================
-- 2. STRATEGY MANAGEMENT
-- =========================================

CREATE TABLE strategies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    strategy_name VARCHAR(150) NOT NULL,
    indicator VARCHAR(100),
    condition_rule VARCHAR(255),
    action ENUM('BUY','SELL','HOLD'),
    risk_level ENUM('LOW','MEDIUM','HIGH'),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 3. MARKET DATA (HISTORICAL + LIVE)
-- =========================================

CREATE TABLE market_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    symbol VARCHAR(50) NOT NULL,
    market_type ENUM('HISTORICAL','LIVE') NOT NULL,
    price DECIMAL(10,2),
    volume BIGINT,
    recorded_at DATETIME
) ENGINE=InnoDB;

-- =========================================
-- 4. HISTORICAL BACKTESTING RESULTS
-- =========================================

CREATE TABLE backtest_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    strategy_id INT NOT NULL,
    total_trades INT,
    winning_trades INT,
    losing_trades INT,
    net_profit DECIMAL(10,2),
    max_drawdown DECIMAL(10,2),
    tested_period VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (strategy_id) REFERENCES strategies(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 5. FORWARD TESTING / SIMULATION LOGS
-- =========================================

CREATE TABLE forward_test_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    strategy_id INT NOT NULL,
    simulated_market VARCHAR(100),
    expected_result VARCHAR(100),
    risk_observation VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (strategy_id) REFERENCES strategies(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 6. LIVE MARKET DECISION SIMULATION
-- =========================================

CREATE TABLE live_simulation (
    id INT AUTO_INCREMENT PRIMARY KEY,
    strategy_id INT NOT NULL,
    current_price DECIMAL(10,2),
    decision ENUM('BUY','SELL','HOLD'),
    confidence_level ENUM('LOW','MEDIUM','HIGH'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (strategy_id) REFERENCES strategies(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 7. AI TRADE REASONING
-- =========================================

CREATE TABLE ai_trade_reasoning (
    id INT AUTO_INCREMENT PRIMARY KEY,
    strategy_id INT NOT NULL,
    decision ENUM('BUY','SELL','HOLD'),
    explanation TEXT,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (strategy_id) REFERENCES strategies(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 8. STRATEGY FAILURE ANALYSIS
-- =========================================

CREATE TABLE strategy_failure_analysis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    strategy_id INT NOT NULL,
    failure_reason VARCHAR(255),
    market_condition VARCHAR(100),
    suggestion TEXT,
    analyzed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (strategy_id) REFERENCES strategies(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 9. TRADER PSYCHOLOGY ANALYSIS
-- =========================================

CREATE TABLE psychology_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    strategy_id INT,
    behavior_type VARCHAR(100),
    description TEXT,
    detected_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    FOREIGN KEY (strategy_id) REFERENCES strategies(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================================
-- 10. REPORTS & DASHBOARD DATA
-- =========================================

CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    report_type VARCHAR(100),
    summary TEXT,
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- 11. ADMIN CONFIGURATION & SETTINGS
-- =========================================

CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE,
    setting_value VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================================
-- END OF SCHEMA
-- =========================================


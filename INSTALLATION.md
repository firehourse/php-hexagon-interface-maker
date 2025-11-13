# ⚙️ Installation & Usage Guide

本文件說明如何安裝、設定與使用  
**php-hexagon-interface-maker**。

---

# 📌 1. 系統需求

| 項目 | 需求 |
|------|------|
| PHP 版本 | **7.3 ~ 8.2** |
| Composer | 建議使用最新版本 |
| OS | Linux / WSL / macOS |

---

# 📌 2. 安裝步驟

### ✅ Step 1 — Clone 本專案

```bash
git clone git@github.com:firehourse/php-hexagon-interface-maker.git
cd php-hexagon-interface-maker
✅ Step 2 — 安裝 composer 套件
```

### ✅ Step 2 — 安裝 composer 套件

```bash
composer install
```
產生：
```bash
vendor/
vendor/autoload.php
```

✅ Step 3 — 設定自動載入（PSR-4）

composer.json 已內建：

```
"autoload": {
    "psr-4": {
        "PTool\\": "src/"
    }
}

```
更新 autoload：
```
composer dump-autoload
```
✅ Step 4 — 設定可執行權限
```bash
chmod +x bin/pTool
```

```bash
php bin/pTool
# 或
./bin/pTool
```
📌 3. 使用方式（CLI Commands）
🔧 指令 1：解析 Interface
```
php bin/pTool sync:repo {InterfacePath}

# 例：
php bin/pTool sync:repo test/OrderRepositoryInterface.php
```
會輸出：
```
Parsing Interface: test/OrderRepositoryInterface.php

Detected Methods:
- getOrder(id): array
- deleteOrder(id)
```
此功能是後續自動同步的基礎。

#🔧 指令 2：檢查 DI 註冊（即將加入）
```bash
php bin/pTool check:di {ModulePath}

# 例：
php bin/pTool check:di /home/webuser/devel/payment/basic/modules/contractRenewal

```

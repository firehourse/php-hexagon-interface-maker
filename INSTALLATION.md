# ⚙️ Installation & Usage Guide

本文件說明如何安裝、設定與使用 **pTool – PHP Hexagonal Architecture Toolkit**。

---

# 1. 系統需求

| 項目       | 需求                  |
| -------- | ------------------- |
| PHP 版本   | **7.3 ~ 8.2**       |
| Composer | 最新版本建議              |
| OS       | Linux / WSL / macOS |

---

# 2. 安裝流程

## ✅ Step 1 — Clone 專案

```bash
git clone git@github.com:firehourse/php-hexagon-interface-maker.git
cd php-hexagon-interface-maker
```

## ✅ Step 2 — 安裝 Composer 套件

```bash
composer install
```

會產生：

```
vendor/
vendor/autoload.php
```

## ✅ Step 3 — 確認 Autoload 設定

專案已內建 PSR-4：

```json
"autoload": {
    "psr-4": {
        "PTool\\": "src/"
    }
}
```

若有修改，請執行：

```bash
composer dump-autoload
```

## ✅ Step 4 — 設定 CLI 執行權限

```bash
chmod +x bin/pTool
```

執行方式：

```bash
php bin/pTool
# 或
./bin/pTool
```

---

# 3. CLI 指令說明

## 🔧 指令 1：解析 Interface & 同步 Repository

```
php bin/pTool sync:repo {InterfacePath}
```

### 範例：

```
php bin/pTool sync:repo test/OrderRepositoryInterface.php
```

### 功能：

* 解析 interface
* 顯示所有 methods
* 自動建立或補齊 Repository class（若已存在）

### 輸出範例：

```
Parsing Interface: test/OrderRepositoryInterface.php

Detected Methods:
- getOrder(id): array
- deleteOrder(id)
```

---

## 🔧 指令 2：檢查 DI 註冊（即將加入）

```
php bin/pTool check:di {ModulePath}
```

### 範例：

```
php bin/pTool check:di /home/webuser/devel/payment/basic/modules/contractRenewal
```

### 功能（預計）：

* 掃描 module/config/common.php
* 檢查所有 Interface 是否已綁定至 DI
* 檢查 implementation class 是否存在
* 檢查實作是否符合 interface（方法缺失、型別錯誤等）
* 未來支援 `--fix` 自動補齊

---

# 4. 後續功能 Roadmap（摘要）

* DI Mapping 自動同步
* 六角架構資料夾自動生成 (`make:hexagon`)
* 自動註冊 Repository 至 common.php
* 自動補齊 typehint / imports
* VSCode Extension
* RoadRunner File-Watcher 模式

---

# 5. 問題回報

如遇問題或想增加新功能，歡迎開 issue 或 contribution。

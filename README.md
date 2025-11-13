# 🧰 php-hexagon-interface-maker
**Yii2 + 六角架構 Interface ↔ Repository 自動同步工具**  
支援 PHP 7.3+，適用於六角架構常用的 interface / repository boilerplate 管理。

---

## 🎯 專案目的

在 Yii2 + Hexagonal Architecture 開發時，往往需要手動：

- 定義 `OrderRepositoryInterface`
- 建立對應 `OrderRepository`
- 補上所有方法與參數
- 在 `config/common.php` 註冊 DI mappings

本工具可協助：

### ✔ 自動解析 interface  
### ✔ 找出缺少的實作方法  
### ✔（未來）自動生成或補齊 Repository  
### ✔ 檢查所有 interface 是否已註冊到 DI  

---

## 🚀 功能列表

### ✔ 已完成（Step One — MVP）

- `sync:repo {InterfacePath}`  
  - 解析 interface  
  - 顯示 method name / params / return type

### ✔ 已設計（Step Two）

- `check:di {ModulePath}`  
  - 掃描 module 下所有 interface  
  - 檢查是否在 `config/common.php` 中註冊

### 🧩 未來功能

- 自動產生 Repository 缺失方法  
- 自動修補 typehint  
- 自動補上 use imports  
- VSCode extension  
- RoadRunner file-watcher background mode  

完整 Roadmap 請見：`INSTALLATION.md`

---

## 📦 安裝與使用

請參考文件：

👉 **[INSTALLATION.md](./INSTALLATION.md)**

內容包含：

- 如何安裝 composer
- 安裝 php-parser
- 設定 autoload
- 設定可執行權限 (`chmod +x bin/pTool`)
- 如何執行 CLI 指令

---

## 📁 專案結構

/bin
└── pTool # CLI 入口
/src
├── Commands
│ ├── SyncRepositoryCommand.php
│ └── CheckDiCommand.php (未來)
└── Parser
└── InterfaceParser.php
/test
/vendor
composer.json
README.md
INSTALLATION.md

---

## 📜 License
MIT License


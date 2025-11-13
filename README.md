📦 php-hexagon-interface-maker

自動同步 Interface ↔ Repository、檢查 DI 註冊的開發輔助工具（for Yii2 + 六角架構）

🎯 專案目的

在公司使用 Yii2 + 六角架構 (Hexagonal Architecture) 開發時，常常會遇到：

❌ 問題 1：

定義一個 OrderRepositoryInterface 後，需要手動維護：

OrderRepository class

方法簽名（參數、型別、return type）

忘記補方法時會出錯

❌ 問題 2：

在 module/config/common.php 內的 DI container，常常忘記註冊 interface → implementation。

🧩 解決方案：本工具

當你新增或修改 interface，本工具會自動生成 / 校正 implementation，並可檢查 DI 是否正確註冊。

🧱 Step One — CLI 工具 MVP（已完成）

核心功能已建立：

✔ 基礎環境

composer 初始化

安裝 nikic/php-parser

自動載入 (autoload)

可在 PHP 7.3 ~ 8.2 執行（兼容）

✔ 已完成功能

sync:repo {InterfacePath}

解析 interface AST

列出 method 與 typehint、return type

作為後續自動同步的基礎

🚧 Step Two — Repository 自動同步（進行中）

下一階段將加入：

🔧 1. ImplementationParser

解析現有 OrderRepository 方法列表，取得：

已實作方法

方法簽名

import / namespace

🔧 2. 方法差異比對

找出：

interface 新增的方法

缺少的 implementation

簽名不一致的方法（參數錯誤）

🔧 3. RepositorySynchronizer

自動補上：

缺少的方法 stub

正確的參數與型別宣告

🔧 4. ImplementationWriter

使用 AST or EOF 模板寫回 PHP

自動補上 use imports

自動格式化輸出

這將讓你在 Laravel IDE Helper 等等的便利感，移植到 Yii2 六角架構。

🧩 Step Three — 新增功能「檢查 DI 是否註冊完成」（新）

已設計並準備加入 CLI：

check:di {ModulePath}

例：

php bin/pTool check:di /home/webuser/devel/payment/basic/modules/contractRenewal


功能：

掃描 module 下所有 interface (interfaces/**)

載入 config/common.php

檢查所有 interface 是否有在：

container.definitions

container.singletons
註冊

輸出：

[OK] OrderRepositoryInterface 已註冊
[MISSING] BillingRepositoryInterface 未註冊


後續擴充：

--fix 參數：自動幫你把 missing 的 interface 填進 common.php

🚀 Step Four — VSCode Extension（未來）

未來將提供 VSCode 插件：

🔥 可能功能：

Code Action（按小燈泡 → Sync Repository）

檔案右鍵 "Sync Implementation"

Command Palette：> Sync Repository

自動同步 on save

自動 DI 註冊提示

🌀 Step Five — RoadRunner / Worker 模式（未來）

支援 long-running service：

檔案監控（file watcher）

監控 interface 變動 → 即時同步 repository

減少啟動開銷

適合作為「背景同步器」運作

📌 專案願景（結語）

本工具最終將能：

自動生成六角架構 boilerplate

自動維持 interface 與 implementation 一致

自動檢查並提示 DI 設定異常

與 VSCode 深度整合

成為 Yii2 六角架構開發的生產力神器
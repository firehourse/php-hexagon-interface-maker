📄 README — Step One 計畫（Repository Auto-Sync Tool）
🎯 目標

建立一個工具，當我在公司開發 Yii2 + 六角架構時：

定義 OrderRepositoryInterface
→ 自動同步 OrderRepository

自動補齊缺少的方法

方法簽名保持一致（參數、typehint、return type）

未來可與 VSCode extension 整合，一鍵同步

🧱 Step One — 核心 CLI 工具
已完成（MVP 第一階段）

✔ 初始化 composer
✔ 安裝 nikic/php-parser
✔ 建立 CLI：bin/pTool
✔ 完成 interface 解析
✔ 能列出所有 methods 與 type

下一步（第二階段）

⬜ 建立 ImplementationParser（解析現有的 repository）
⬜ 建立方法差異比對（找出缺少的方法）
⬜ 建立 RepositorySynchronizer（補上缺失的方法）
⬜ 建立 ImplementationWriter（生成新方法 AST 寫回）
⬜ 自動補上 use imports
⬜ 自動格式化輸出

🚀 未來擴展
Step Two — 開發 VSCode extension

Code Action（燈泡同步）

右鍵 "Sync Implementation"

Command Palette 支援

autoSync on save

Step Three — RoadRunner 版本（檔案監控）

file watcher 監控 interface 變動

自動觸發同步

以 server mode 運作

結語

此工具將大幅降低 repository boilerplate 成本，實際提升六角架構開發速度。
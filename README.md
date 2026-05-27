# 🧬 GenomicsDB — Мутації та SNP-варіації

> Навчальний веб-проєкт, присвячений молекулярній генетиці: типам мутацій, однонуклеотидним поліморфізмам (SNP), GWAS-дослідженням та персоналізованій медицині в радіобіології.

Проєкт демонструє повний фронтенд-бекенд стек: **HTML5 + CSS3 + Vanilla JS** на клієнті, **PHP 8 + MySQL (PDO)** на сервері, **AJAX (Fetch API)** та **HTMX** для динамічної взаємодії, **Bootstrap 5.3** для адаптивного інтерфейсу.

---

## 📋 Зміст
- [Можливості](#-можливості)
- [Технологічний стек](#-технологічний-стек)
- [Структура проєкту](#-структура-проєкту)
- [Детальний опис файлів](#-детальний-опис-файлів)
- [Установка та запуск](#-установка-та-запуск)
- [API endpoints](#-api-endpoints)
- [Схема бази даних](#-схема-бази-даних)

---

## ✨ Можливості

- 📚 **Навчальний контент** про типи мутацій (substitution, insertion, deletion, frameshift)
- 🧪 **Реальний приклад** — серпоподібноклітинна анемія (SNP rs334, ген HBB)
- 🔬 **Інтерактивний симулятор точкових мутацій** з трансляцією ДНК → мРНК → білок
- 📊 **Manhattan plot** GWAS-даних, намальований на HTML5 Canvas
- 🧬 **Псевдокод алгоритмів** з підсвічуванням синтаксису (4 вкладки: пошук SNP, трансляція, класифікація, χ²-тест GWAS)
- 📑 **Огляд наукової роботи** Barnett et al. (2014) про GWAS та токсичність радіотерапії
- 🗄 **HTMX-таблиця SNP з MySQL** з живим пошуком без перезавантаження сторінки
- ✉️ **AJAX-форма зворотного зв'язку** з валідацією та збереженням у БД
- 🎨 **Bootstrap-дашборд** (`bootstrap_page.php`) у стилі SB Admin із статистикою з API

---

## 🛠 Технологічний стек

| Шар             | Технології                                                  |
|-----------------|-------------------------------------------------------------|
| Розмітка        | HTML5, семантичні теги                                      |
| Стилі           | CSS3 (custom properties, grid, flexbox), Bootstrap 5.3      |
| Іконки/шрифти   | Bootstrap Icons 1.11, Google Fonts (Playfair, JetBrains Mono, Lora) |
| JS-логіка       | Vanilla JavaScript (ES6+), Canvas API, IntersectionObserver |
| AJAX            | Fetch API, HTMX 1.9                                         |
| Бекенд          | PHP 8+, PDO                                                 |
| База даних      | MySQL / MariaDB (utf8mb4)                                   |
| Хостинг (приклад) | ukraine.com.ua (cPanel + phpMyAdmin)                      |

---

## 📁 Структура проєкту

```
Web_HW1-5-main/
├── index.php           # Головна сторінка (7 секцій контенту)
├── bootstrap_page.php  # Bootstrap-дашборд (адмін-стиль)
├── api.php             # REST-подібний бекенд (JSON + HTMX)
├── db_config.php       # Конфігурація БД + допоміжні функції
├── schema.sql          # SQL-скрипт створення таблиць і тестових даних
├── script.js           # Інтерактивна логіка фронтенду
├── style.css           # Основні стилі сайту
└── README.md           # Цей файл
```

---

## 📄 Детальний опис файлів

### 1. `index.php` — головна сторінка (~1 887 рядків)

Багатосекційний односторінковий сайт. PHP-розширення використовується для можливості підключення серверного коду в майбутньому, але наразі файл містить переважно HTML + інлайн CSS + JS.

**Підключені CDN:**
- Bootstrap 5.3.3 CSS + JS Bundle
- Bootstrap Icons 1.11.3
- HTMX 1.9.12
- `style.css` (зовнішній файл)

**Структура сторінки:**

| # | Секція           | ID            | Що містить                                                                 |
|---|------------------|---------------|----------------------------------------------------------------------------|
| — | Навбар           | `<nav>`       | Логотип `⬡ GenomicsDB`, посилання-якорі, кнопка-перемикач «Роздільники», бургер-меню для мобільних |
| — | Hero             | `.hero`       | Заголовок «Мутації та SNP-варіації», canvas-анімація ДНК-спіралі (`#hero-canvas`), декоративна стрічка пар основ |
| 1 | Типи мутацій     | `#mutations`  | Велика статистика (геном людини), 4 картки: Substitution / Insertion / Deletion / Frameshift із візуалізацією послідовностей |
| 2 | Реальний приклад | `#example`    | Серпоподібноклітинна анемія: HBB кодон GAG (Glu) → GTG (Val); **інтерактивний симулятор** точкових мутацій |
| 3 | SNP & GWAS       | `#snp`        | Пояснення SNP, ключова статистика (>600 млн SNP, >1.1 млрд у dbSNP), три сценарії: missense / synonymous / nonsense на рівні кодонів |
| 4 | Псевдокод        | `#pseudocode` | 4 вкладки з алгоритмами: SNP Detection, Translation, Classification, GWAS χ²-test. Підсвітка синтаксису через теги `<kw>`, `<fn>`, `<str>`, `<cm>`. Кнопка «Копіювати» |
| 5 | Дослідження      | `#research`   | Картка з науковою роботою Barnett GC et al. (Radiother Oncol. 2014) про GWAS і пізню токсичність радіотерапії — мета, когорта, методи, результати |
| 6 | HTMX-таблиця БД  | `#db-table`   | Жива таблиця `snp_variants` з MySQL. Поле пошуку з `hx-trigger="keyup delay:400ms"`. Bootstrap Alert та Accordion із поясненням |
| 7 | Контактна форма  | `#contact`    | AJAX-форма (Fetch POST → `api.php?action=contact`), клієнтська + серверна валідація |
| — | Footer           | `<footer>`    | Підпис, посилання на зовнішні ресурси                                       |

**Інлайн `<style>` та `<script>` блоки в `index.php`:**
- CSS для симулятора (`.sim-wrap`, `.sim-controls`)
- CSS для контактної форми (`.ajax-form`)
- CSS для HTMX-таблиці (`.snp-table`, `.htmx-section`)
- CSS для кнопки «Роздільники» та бургер-меню
- CSS для веселкових роздільників між секціями (`.rb-sep` з 7 кольоровими варіантами)
- JS-логіка кнопки «Роздільники» (вставляє `<div class="rb-sep">` між секціями)
- JS-логіка бургер-меню для мобільних
- JS-обробник AJAX-форми (`fetch('api.php?action=contact')` з валідацією)

---

### 2. `bootstrap_page.php` — Bootstrap-дашборд (~606 рядків)

Окрема сторінка-дашборд, адаптована зі шаблону **StartBootstrap SB Admin**. Демонструє використання Bootstrap-компонентів.

**Будова:**
- **Sidebar (`#sidebar`)** — фіксована бокова панель: логотип, секції навігації («Навігація», «База даних», «Зовнішні ресурси»), посилання повернення на головну
- **Topbar (`.topbar`)** — кнопка-гамбургер (для мобільних), назва, бейдж стеку технологій, інфо про БД
- **Alert** — `alert alert-info` із посиланням на оригінальний SB Admin
- **Картки статистики (`#stats-section`)** — 4 stat-cards (загальна кількість SNP, missense, nonsense, унікальні гени). Значення підвантажуються через `fetch('api.php?action=snp_list')`
- **HTMX-таблиця (`#snp-section`)** — той самий `htmx_snp_table` endpoint, що й на головній, але в стилі дашборду
- **Bootstrap Tabs** — вкладки «Схема БД» / «API endpoints» / «HTMX інфо»
- **Bootstrap Toast** — спливаюче повідомлення «Дані з БД завантажено»

**Вбудована JS-логіка:**
- AJAX-запит на `api.php?action=snp_list` для статистики
- Активна підсвітка табів
- Функція `showToast()` для виклику Bootstrap Toast

**Кастомна тема** поверх Bootstrap у дусі GitHub Dark (`#0d1117`, `#161b22`, акценти `#238636` зелений та `#1f6feb` синій).

---

### 3. `api.php` — REST-подібний бекенд (~171 рядок)

Єдиний контролер, що маршрутизує запити за параметром `?action=`. Підтримує CORS та OPTIONS preflight.

**Endpoints:**

| Action             | Метод | Формат відповіді | Призначення |
|--------------------|-------|------------------|-------------|
| `snp_list`         | GET   | JSON             | Повертає всі SNP-варіанти з БД, відсортовані за хромосомою та позицією |
| `snp_search`       | GET   | JSON             | Пошук SNP за `rs_id`, `gene` або `disease` (параметр `q`, prepared statement з LIKE), ліміт 50 |
| `contact`          | POST  | JSON             | Приймає `name`, `email`, `subject`, `message` (JSON або form-data), валідує (довжина, формат email), зберігає у `contact_requests` разом з IP |
| `htmx_snp_table`   | GET   | HTML-фрагмент    | Повертає готові `<tr>` для таблиці. Підтримує параметр `search`. Кольорове кодування `consequence`, відсотковий формат MAF |

**Безпека:**
- Усі SQL-запити — через PDO prepared statements (захист від SQL-ін'єкцій)
- `htmlspecialchars()` на всіх виводах HTML
- Серверна валідація на `contact`-endpoint (422 при помилках)
- Помилки PDO повертаються як JSON 500, не виводяться в HTML

---

### 4. `db_config.php` — конфігурація БД та утиліти (42 рядки)

Містить:
- **Константи підключення** до MySQL: `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_PORT`, `DB_CHARSET`
- **`getDB(): PDO`** — singleton-функція, що повертає PDO-підключення з налаштуваннями:
  - `ERRMODE_EXCEPTION` — викидає винятки замість мовчазних помилок
  - `FETCH_ASSOC` — асоціативні масиви за замовчуванням
  - `EMULATE_PREPARES = false` — справжні prepared statements на стороні MySQL
- **`jsonResponse(array $data, int $status = 200)`** — універсальна відповідь у JSON: встановлює статус, заголовок `Content-Type`, CORS-заголовок та припиняє виконання

> 🔐 **Перед публікацією** замініть `DB_PASS` (зараз заглушка `'PASSWORD'`) на реальний пароль і **не комітьте файл у Git** (додайте в `.gitignore` або використовуйте env-змінні).

---

### 5. `schema.sql` — структура бази даних (58 рядків)

SQL-скрипт для першого запуску. Виконати:
```bash
mysql -u root -p < schema.sql
```

**Створює БД** `genomics_db` з `utf8mb4` та **дві таблиці**:

#### `snp_variants` — каталог SNP
| Поле          | Тип                                                                          | Опис                                         |
|---------------|------------------------------------------------------------------------------|----------------------------------------------|
| `id`          | INT UNSIGNED AUTO_INCREMENT                                                  | Первинний ключ                               |
| `rs_id`       | VARCHAR(20) UNIQUE                                                           | dbSNP-ідентифікатор (rs334)                  |
| `gene`        | VARCHAR(50)                                                                  | Назва гену (HBB, BRCA1…)                     |
| `chromosome`  | TINYINT UNSIGNED                                                             | Номер хромосоми (1–22, 23=X, 24=Y)           |
| `position`    | BIGINT UNSIGNED                                                              | Координата у геномі (GRCh38)                 |
| `ref_allele`  | CHAR(1)                                                                      | Референсний нуклеотид (A/T/G/C)              |
| `alt_allele`  | CHAR(1)                                                                      | Альтернативний нуклеотид                     |
| `maf`         | DECIMAL(6,4)                                                                 | Minor Allele Frequency (0–1)                 |
| `consequence` | ENUM(`missense`, `nonsense`, `silent`, `splice`, `intergenic`, `regulatory`) | Тип наслідку мутації                         |
| `disease`     | VARCHAR(255)                                                                 | Асоційоване захворювання                     |
| `created_at`  | TIMESTAMP                                                                    | Автоматичний час створення запису            |

**Тестові дані:** 10 реальних SNP — rs334 (HBB), rs1801516 (ATM), rs25487 (XRCC1), rs2788612 (KCND3), rs1042522 (TP53), rs28897696 (BRCA1), rs80357914 (BRCA2), rs1801321 (RAD51) тощо.

#### `contact_requests` — повідомлення з форми
| Поле           | Тип            | Опис                          |
|----------------|----------------|-------------------------------|
| `id`           | INT UNSIGNED   | Первинний ключ                |
| `name`         | VARCHAR(100)   | Ім'я відправника              |
| `email`        | VARCHAR(150)   | Email                         |
| `subject`      | VARCHAR(200)   | Тема (опційно)                |
| `message`      | TEXT           | Текст повідомлення            |
| `ip_address`   | VARCHAR(45)    | IP-адреса (IPv4/IPv6)         |
| `submitted_at` | TIMESTAMP      | Час відправки                 |

---

### 6. `script.js` — інтерактивна логіка фронтенду (~341 рядок)

Сучасний vanilla-JS у `'use strict'` без зовнішніх залежностей.

**Модулі:**

| Блок                  | Що робить                                                                                                                                                                            |
|-----------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Scroll Reveal**     | `IntersectionObserver` з порогом 0.12 додає клас `.visible` елементам із `.reveal`, коли вони з'являються у viewport — для плавної анімації появи                                    |
| **`CODON_TABLE`**     | Повна таблиця 64 кодонів РНК → 3-буквений код амінокислоти (Phe, Leu, …, Stop)                                                                                                       |
| **`dnaToRna()`**      | Заміна `T → U` у послідовності                                                                                                                                                       |
| **`splitCodons()`**   | Розбиття РНК на триплети                                                                                                                                                             |
| **`translateCodons()`** | Маппінг кожного кодону на амінокислоту                                                                                                                                             |
| **`buildSimulator()`** | Логіка симулятора у секції «Приклад»: бере ДНК, позицію та новий нуклеотид → застосовує мутацію → рендерить «до/після» з підсвіткою заміненої бази → транслює обидві в білки → класифікує мутацію (silent / missense / nonsense / non-coding) |
| **`drawManhattan()`** | Малює Manhattan plot для GWAS на `<canvas>`. Генерує детермінований набір точок (по 22 хромосомах) із більшими `-log10(p)` для виділених рисових SNP, малює осі, поріг p<5×10⁻⁸, легенду. Перемальовується на `resize` |
| **`initTabs()`**      | Логіка перемикання вкладок псевдокоду (`.tab-btn` ↔ `.tab-pane`)                                                                                                                     |
| **`initCopyButtons()`** | Кнопки «Копіювати» біля блоків коду — копіюють текст у буфер обміну через `navigator.clipboard.writeText()`                                                                       |
| **`initNavHighlight()`** | Підсвічування активного пункту меню залежно від позиції скролу (через `IntersectionObserver` на секціях)                                                                          |
| **`initHeroCanvas()`** | Анімація ДНК-спіралі частинками на `#hero-canvas` у героїчній секції — два сінусоподібні ланцюги «нуклеотидів», що рухаються з часом                                                |

**Точка входу:** `DOMContentLoaded` → послідовний виклик усіх `init*()`.

---

### 7. `style.css` — основні стилі (~554 рядки)

Містить CSS-змінні (`:root`), типографіку, всі секції лендингу.

**Дизайн-токени (custom properties):**
- Фони: `--bg` (#060a10), `--bg2`, `--bg3`
- Акценти: `--accent` (синій #3b82f6), `--accent2`, `--accent3`, `--gold`, `--red`, `--green`, `--purple`
- Текст: `--text`, `--text-muted`, `--text-dim`
- Шрифти: `--mono` (JetBrains Mono), `--serif` (Playfair Display), `--body` (Lora)

**Логічні блоки стилів:**
- **Noise Overlay** — інлайн SVG з `feTurbulence` створює зернисту текстуру (fixed позиція, дуже низька opacity)
- **Grid Background** — синя сітка 60×60 px на фоні (`.grid-bg`)
- **Hero** — повноекранна геро-секція з canvas-анімацією
- **Nav** — фіксований навбар з backdrop-blur
- **Main Layout** — `.container` (max-width), padding для секцій
- **Section Header** — `.section-tag`, `.section-title`, `.section-lead`
- **Mutation Cards** — картки 4 типів мутацій з кольоровими акцентами зліва
- **Sequence Visual** — стилі візуалізації послідовностей ATGC з кольоровими нуклеотидами (`.base-A`, `.base-T`, `.base-G`, `.base-C`, `.base-mut`)
- **SNP Section** — `.snp-layout`, бейджі (gwas/radio/pharm/cancer), статистичні картки
- **Codon Table** — стилі візуалізації трьох сценаріїв SNP (synonymous/missense/nonsense)
- **Pseudocode** — стилі для блоків коду: `<kw>` (ключові слова), `<fn>` (функції), `<str>` (рядки), `<cm>` (коментарі), `<type>` (типи), `<num>` (числа); вкладки, кнопка копіювання
- **Research** — стилі картки наукової роботи
- **GWAS Visual** — стилі для Manhattan-плоту
- **Footer** — простий футер
- **Info Box** — спливаючі інформаційні блоки з іконкою-емодзі
- **Scroll Reveal** — `.reveal` (opacity 0, translateY 30px) → `.reveal.visible` (opacity 1, translateY 0)
- **Divider** — декоративні роздільники з підписами
- **Responsive** — медіа-запити для мобільних (< 700px, < 1000px)

---



---

## 🗄 Схема бази даних

```
genomics_db
├── snp_variants          # каталог SNP (10 тестових записів)
│   └── PK: id
│   └── UNIQUE: rs_id
└── contact_requests      # повідомлення з AJAX-форми
    └── PK: id
```

---

## 📜 Зовнішні ресурси

- [NHGRI-EBI GWAS Catalog](https://www.ebi.ac.uk/gwas/)
- [NCBI dbSNP](https://www.ncbi.nlm.nih.gov/snp/)
- [Radiogenomics Consortium](https://radiogenomics.org)
- [ClinVar](https://www.ncbi.nlm.nih.gov/clinvar/)
- [Barnett GC et al. (2014) DOI: 10.1016/j.radonc.2014.02.012](https://doi.org/10.1016/j.radonc.2014.02.012)

---

## 📝 Відповідність завданням Web HW1–5

| HW | Тема                                | Реалізація                                                                       |
|----|-------------------------------------|----------------------------------------------------------------------------------|
| 1  | HTML/CSS — статичний сайт           | `index.php` + `style.css` (семантичні теги, адаптивність)                        |
| 2  | JavaScript — інтерактивність        | `script.js` (симулятор, canvas-анімації, scroll reveal, табы)                    |
| 3  | Bootstrap                           | `bootstrap_page.php` + Bootstrap-компоненти в `index.php` (Alert, Accordion, Badge) |
| 4  | PHP + MySQL                         | `api.php` + `db_config.php` + `schema.sql` (PDO, prepared statements)            |
| 5  | AJAX + HTMX                         | Fetch-форма контактів + HTMX-таблиця SNP із пошуком                              |

---



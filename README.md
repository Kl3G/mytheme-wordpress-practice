# Sassを使用したWordPressのCustom Theme  
### 参考  
- https://sass-guidelin.es/

---

## Themeの紹介  
Robert C. MartinのClean Architectureを  
学び、その内容を記録する個人メモのTheme。 
#### (`Dummy data`が入っている)  
#### Homepage
<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/384b284d-9956-4ef5-bdd7-1e84d3e4d3ed" />

#### Static page
<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/bf0470ab-8a89-4be7-b2aa-d74dc424a4e5" />

#### Single post
<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/8f0ba2b8-a59e-4820-958f-464fd92192c2" />

#### Archive(Responsive)
<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/749a4a0c-9e78-485d-98c2-78c5872a49e3" />

<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/8e5cd17a-4f03-454f-9723-f607de40fbab" />

<img width="600" height="600" alt="image" src="https://github.com/user-attachments/assets/38362ca0-d267-44ec-ae08-0b8d6d481dd6" />



---

## Templates Structure  
```
original/
├── front-page.php        # Homepage
├── page.php              # Static page
├── single-concept.php    # Single post
└── taxonomy-layer.php    # Archive pages (Entities, Use cases..)
```

---

## HTML Structure
Related issue: https://github.com/Kl3G/mytheme-wordpress-practice/issues/7

---

## Sass Structure  
```
scss/
├── abstracts/
│   ├── _mixins.scss
│   └── _variables.scss
│
├── base/
│   ├── _base.scss
│   ├── _reset.scss
│   └── _typography.scss
│
├── components/
│   └── _card.scss
│
├── layout/
│   ├── _footer.scss
│   ├── _header.scss
│   └── _main.scss
│
├── pages/
│   └── _taxonomy-layer.scss
│
├── themes/
├── vendors/
│
└── style.scss
```

---

## Themeの設計で想定したもの  
1. カスタム投稿タイプ  
`concept`(自分の考え)  
2. カスタムフィールド  
`URL`(参考サイト)  
3. カスタムタクソノミー  
`layer`(Architectureの4階層)  
4. 簡単なレスポンシブ対応  
各`layer`の`Template(taxonomy-layer.php)`に適用  

---

## Sass  
`CSS`の重複, 構造崩れ, 影響範囲の予測の困難などの問題を  
解決するための`CSS preprocessor`

---

## SCSS  
`Sass`は`CSS`文法と異なって不便だった。  
そのため、CSS文法と完全に互換できるよう、新しく作った文法。

---

## Sassの主な機能  
1. `Variables`  
値を変数に保存して再使用。  
(色 / 大きさ / Space / Shadow/Break Pointなの)  
2. `Nesting`  
`component`内の関連する`selector`をまとめて書いて  
`CSS`の`selector`の関係を明確に見せてくれる便利機能(設計哲学は込めてない)。  
(あと、可読性 / `&`で文字の反復減少 / タイポ防止などの利点)
```
.button {
  background: blue;

  &:hover {
    background: darkblue;
  }

  &:disabled {
    opacity: 0.5;
  }
}
```
3. `Mixins`  
Related issue: https://github.com/Kl3G/mytheme-wordpress-practice/issues/13
4. `Modules`  
`@use`で呼び出した一つの`SCSS`file。
5. `Partials`  
`SCSS`のFilesを意味する。  
一つの`File`の責任が多くなるのを防止。

---

## Sass 7-1 Architecture  
Related issue: https://github.com/Kl3G/mytheme-wordpress-practice/issues/8

---

## 使いたかったが、活用できなかった設計および機能  
`BEM`(Block Element Modifier)  

### 使いたかった理由  
1. 構造変更時に影響範囲を最小化するため  
(要素間の`style`関係は改めて考える必要がある。)
2. 大規模な崩れを減らすため
3. `style`を構造基準ではなく役割基準で適用するため

### 核心哲学  
#### 構造基盤設計 X   
(何々の中にあるから、このように設計する)  
#### 役割基盤設計 O  
(何々という役割だから、このように設計する)  
1. 要素を直接選択することを避ける  
2. 要素に役割を与え、その役割に`style`を適用する  
3. 役割は親子構造に依存せず、独立して存在する 

### 設計の流れ 
`Component`の定義(`Markup` + `class`区分) -> `HTML` -> `SCSS`  
`BEM`は`HTML`構造を見て名前を付ける方式ではなく、  
`Component`を`Markup`段階で先に定義してから、`HTML`を構成する方式である。  

### BEMの本質  
単純な名付けの規則ではない。  
`CSS`設計哲学まで含む。  

---

## 今回のプロジェクトを通して考えたこと  
この`Project`で重点を置いたのは、実装そのものよりも設計と構造である。  
最近は`AI`を活用しているため、  
技術的な実装に大きな難しさはないと思っている。
しかし、基礎設計や構造の判断は、  
最初から最後まで人が責任を持つ部分だと感じている。  
基礎が不安だったり、`code`が強く結合すると、  
維持や保守がほとんど不可能になることがあるからだ。  
個人的な考えかもしれないけど、  
このような理由で構造や設計を`AI`に任せるのは、まだ良くないと思っている。  

設計や構造の概念は自分でしっかり理解する必要がある。  
そうしてこそ、`AI`に自分の意図を正確に伝えることができる。  
また、`AI`が出した誤った情報が正しいかどうかを揺らぐことなく自分で判断できる。  
これが先にあってこそ、`AI`が生成したコードを早く理解でき、  
開発はもちろん、正確な保守も可能になると思っている。  

---

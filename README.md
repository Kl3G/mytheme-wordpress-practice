# WordPress 요청 처리 흐름과 WP_Query 동작 정리  
## 参考  
- https://developer.wordpress.org/reference
---

## WordPress의 요청 처리 흐름  
1. 관리자가 page | post를 등록한다.
2. wp_posts(table)에 page | post recode 생성(post_type='page'|'post')된다.
3. 사용자가 https://site.com/something/ 요청을 보낸다.
4. Web server가 physical file | directory인지 확인한다.
5. physical이면 physical로 응답, 아니면 WordPress의 index.php로 전달한다.
6. WP는 URL path를 WP::parse_request()로 해석(Rewrite Rule)하고,
7. query vars 배열로 만든 뒤 WP_Query::parse_query()에 전달한다.
8. parse_query()는 Query_Vars[], Conditional Flags(conceptual)설정한다.
9. get_posts()는 설정된 query_vars로 wp_posts를 조회하고,
10. DB Result Data(conceptual) 설정, Conditional Flags 보정한다.
11. template-loader.php는 Conditional Flags를 참조, 실행 PHP Template 결정한다.
12. 해당 template은 WP_Query 객체의 Field를 바탕으로 HTML 생성 후 출력한다.

## WordPress의 URL 이해 방식  
URL의 Query를 이해해서 처리한다.  
쿼리가 있는 URL은 WordPress가 바로 이해할 수 있다.  
URL에 query가 없는 경우, 해당 URL 형식에 대응하는 Rewrite Rule가 존재해야 한다.  
rewrite rule match 실패해도 URL에 있던 query로 형태로 query vars 구성하지만,  
URL에 query가 없는 경우라면 query vars 배열은 비어있는 상태로 전달된다.  
따라서, Query_Vars[]도 비어있게 되고 WP_Query::get_posts() --> is_404(flag) = true,  
template-loader.php가 404.php를 선택하게 된다.  
단, permalink가 %postname% 경우에는 RR 매칭 실패 시에도 slug 기반 보조 판단으로  
pagename | name 이 구성될 수 있고, DB 조회도 시도된다.

## Permalink  
미리 설정된 형식에 post 정보를 대입하여 만든 URL.  
post 하나를 식별해서 그 post에 접근할 수 있는 URL을 만든다.  
= post의 정보를 바탕으로 URL의 형식을 변경할 수 있다.  
URL의 Query는 사람이 읽기 어렵고, 외부에 노출하기 적절치 않다.  
--> 사람에게 친화적인 URL가 필요하다.

## Rewrite Rule  
WordPress가 이해할 수 없는 요청 URL(query X)을  
규칙에 따라 WordPress가 이해하는 형태(query O)로 변환한다.  
RR의 최우선 작업은 PR 형식으로 query vars(pagename | name) 생성.  
PR 형식이 아니더라도, URL을 pagename 또는 name으로  
변환할 수 있으면 WordPress는 그 요청을 “이해”할 수 있다.

## Permalink에 따라 달라지는 Rewrite Rule  
permalink 형식의 URL에 따라 Rewrite Rule이 구성하는 query도 달라진다.  
※ Post name (http://mysite.local/sample-post/)  
= index.php?name=post-name  
※ Day and name (http://mysite.local/2026/02/04/sample-post/)  
= index.php?year=2026&monthnum=2&day=4&name=post-name  
몇 가지 장점을 위해 같은 post의 표현 전략을 바꿔야 한다.  
1. Post name은 이름만 출력, Day and name은 이름 + 날짜 (출력 다양성).
2. 출력 성격이 바뀌어도 post를 재사용할 수 있다.

## Query에 따라 다르게 선택되는 Template  
같은 post라도 표현 전략이 달라진다 --> post의 출력 template도 달라져야 한다.  
permalink 형식이 post의 표현 전략, rewrite rule은 전략을 query로 변환해 template 선택.

## WP::parse_request()  
Rewrite Rules 정규식 적용.  
요청 URL(permalink)를 확인해서 WP::$query_vars을 설정.  
대표적으로 "pagename=about" 또는 "name=hello-world"

## parse_request()의 pagename | name 판단 방식  
Permalink가 Post name(/%postname%/)일 때,  
URL 요청만으로 Rewrite rule(for page | post)을 구분하기 어렵다.  
parse_request()로 page 존재 여부만 DB(wp_posts)에서 확인하고  
존재하면 Page(pagename), 없으면 다음 규칙(Post(name))을 적용한다.  
Query Vars는 parse_request()가 확정된 형태로 전달하기 때문에 설정 후 수정되지 않는다.  
즉, WP_Query는 get_posts()로만 wp_posts(table) 조회하지 않고  
parse_request()로도 wp_posts(table) 조회해 적용할 Rewrite rule (for page or post) 구분한다.

## WP_Query::parse_query()  
WP::$query_vars 배열을 전달받아서 WP_Query::$query_vars, Flags 구성.  
테이블을 조회하는 게 아님, wp_posts(table)를 조회할 형태를 준비.  
parse_query()는 WP_Query 인스턴스가 생성되면 기본적으로 호출된다.  
메인 쿼리든 서브 쿼리든 WP_Query가 생성되면,  
기본적으로 parse_query()는 호출된다.  
대부분의 메인 쿼리는 Front page / Posts page 판단을 반드시 거친다.  
Front page / Posts page는 URL로는 절대 알 수 없기 때문.  
※ 내부에서 get_option()로 wp_options(table) 조회 --> "is_front_page", "is_posts_page" 설정.  
"p" 또는 "page_id"에 값이 있는 상태로 전달되어도,  
"queried_object_id"와 "queried_object"는 get_posts() 이후에 채워진다.

## WP_Query::get_posts()  
parse_query()로 설정된 Query Vars 바탕으로 DB 실제 조회.  
WP_Query 필드 확정하는 최종 과정.  
중심 테이블: "wp_posts"  
필요 시 JOIN: "wp_postmeta", "wp_term_*", "wp_users"  

---  
# WP_Query의 필드
## Query_Vars[]  
※ parse_query()로 설정.  
post_type =  
name = (post slug)  
pagename = (page slug)  
p =  
page_id =

## Conditional Flags  
※ parse_query()로 설정, get_posts()로 보정.  
is_page =  
is_single = Is thie a single post?  
is_singular = Is this a single content item?  
is_home = Is this the blog posts index?  
is_front_page = Reading setting.  
is_posts_page = Reading setting.

## DB Result Data  
※ get_posts()로 설정.  
(아래 괄호는 참조 테이블, 이 프로퍼티들이 DB 컬럼은 아님)  
show_on_front = (wp_options)  
page_on_front = (wp_options, Homepage)  
page_for_posts = (wp_options, Posts page)  
queried_object = Represents the page; not rendered in template. (wp_posts)  
queried_object_id = ID of the queried_object. (wp_posts)  
posts = An array of posts; contains one item for a single post | page. (wp_posts)  
post = The current post object in the Loop. (wp_posts)  
★ Flag에 따라 posts에 담기는 데이터의 종류와 개수가 달라진다.  
페이지 정보일 수도 있고 게시글 목록일 수도 있고, 단일 게시글일 수도 있다.

---  

## WordPress의 Reading Setting 확인 절차  
Page가 등록될 때 고유 정보가 DB(wp_posts)에 저장.  
Reading Setting에서 page 설정 --> DB(wp_options)의 레코드가 변경.  
option_name = page_on_front | page_for_posts  
option_value = page ID  
요청 오면 parse_request()로 query vars (pagename | name) 생성,  
parse_query() 내부의 get_option() --> "is_front_page", "is_posts_page" 설정.

## Homepage와 Posts page 구분 흐름과 핵심 Flags  
### Your latest posts  
※ wp_options) show_on_front(column) = posts  
요청 --> parse_query() 안에서 get_option() -->  
wp_options 확인 --> flag 설정 is_front_page = true  
is_posts_page = false  
is_home = true (글 목록을 의미)  
is_page = false (이유 => show_on_front(column) = posts)  
결과 = Posts Shown On Front  

### Homepage (A static page)  
※ wp_options) show_on_front = page,  
page_on_front = Homepage ID, page_for_posts = Posts page ID  
요청 --> parse_query() 안에서 get_option() --> wp_options 확인 -->  
is_front_page = true  
is_posts_page = false  
is_home = false  
is_page = true  
결과 = Page Shown On Front  

### Posts page (A static page)  
is_front_page = false  
is_posts_page = true  
is_home = true  
is_page = false (page처럼 보이지만, posts 목록으로 인식.)  

### ★ WordPress는 Page Type을 판정하지 않는다.  
WordPress는 flags만 만든다, Page Type은 그 flags를 사람이 묶은 개념.  
즉, Page Type = 사람이 Conditional Flags를 조합해서 붙인 추상적 개념.  
Page type은 WordPress 요청 분기의 개념적 분류(카테고리).  
WordPress는 요청을 받으면 Conditional Flags를 세팅하고.  
template-loader.php는 이걸 바탕으로 템플릿을 선택.

## 단일 Post 요청 처리 흐름와 핵심 Flags  
1. parse_request() --> page name 아닌 name 포함한 query vars 배열 전달.
2. parse_query() --> 전달받은 배열을 바탕으로 Query_Vars 설정.
3. get_posts() --> Query_Vars로 DB 조회(post name의 hit data 등).
4. Result Data 설정(post content), Conditional Flags(name = is_single) 보정.

## Static page 요청 처리 흐름와 핵심 Flags  
1. Flags (is_singular) 확인
2. is_singular === true --> Singular Page
3. Flags (is_page , is_single) 확인
4. is_page === true --> Static Page | is_single === true --> Single Post Page 분기.

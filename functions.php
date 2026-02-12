<?php

// Enqueue styles
function original_enqueue_styles() {
    wp_enqueue_style( // 현재 요청에서 사용할 CSS 파일을 스타일 관리 시스템에 등록.
        'original-style', // WordPress가 이 CSS 파일을 관리할 때 사용하는 이름.
        get_stylesheet_uri(), // 사용할 CSS 파일의 경로.
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
            'add_new_item' => 'Add Concept', // Add button 텍스트 설정.
        ),
        'public' => true, // 외부 공개 여부.
        'has_archive' => true, // archive rewrite rule 생성.
        'rewrite' => array('slug' => 'concept'), // main query 만드는 문자열 설정.
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'), // 입력창 구조.
        'show_in_rest' => true, // 이 글에 API로 접근하게 할지 선택.
        'menu_position' => 5, // 관리자 메뉴에 위치할 순서.
        'menu_icon' => 'dashicons-lightbulb' // concept 표시 아이콘.
    ));
}
add_action('init', 'register_concept_cpt');

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
        <label for="concept_summary">Summary</label><br>
        <textarea id="concept_summary" name="concept_summary" style="width:100%;"></textarea>
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
        'concept_meta_box', // box ID (WP가 이 box를 구분해야 한다.)
        'Concept Fields', // box title
        'render_concept_meta_box', // callback (HTML 출력 함수)
        'concept', // post_type
        'normal', // context (nomal = 본문 아래, side = 오른쪽 등..)
        'default' // priority (context 안에서의 위치 순서)
    );
}
add_action('add_meta_boxes', 'add_concept_meta_box');



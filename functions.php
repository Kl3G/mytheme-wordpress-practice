<?php 
// 리소스 등록은 템플릿 파일에서 하면 안 된다.
// Template 실행은 이미 출력 단계라서 리소스 등록하기에 늦다.
// functions.php에 함수로 등록해 두면,
// WordPress가 PHP Template 실행 전에 등록해 준다.
function original_enqueue_styles()
{
    wp_enqueue_style( // 이 CSS를 WordPress가 관리하는 방식으로 로드해라.
        'original-style',
        get_stylesheet_uri(),
        array(),
        filemtime(get_stylesheet_directory() . '/style.css')
    );
}
add_action('wp_enqueue_scripts', 'original_enqueue_styles');
// 페이지를 그리기 전, CSS/JS를 등록하는 타이밍에 이 함수를 실행해라.
?>
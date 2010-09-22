<?php

// desabilitando o cache
header ("Content-type: image/png");
header('Pragma: no-cache');
header('Cache-Control: private, no-cache, proxy-revalidate');

// é muito mais seguro usar sessoes.
session_start();

// Cria a imagem GD do captcha
$im = imagecreatetruecolor ( 150, 70 )
    or die("Cannot Initialize new GD image stream");

// Preenche de cinza
$bg = imagecolorallocate ( $im, 255, 255, 255 );
imagefill ( $im, 0, 0, $bg );

// Coloca os chanfros aleatorios na imagem
for ( $i = 0 ; $i < 1000 ; $i++ ) {
    $lines = imagecolorallocate ( $im, rand ( 200, 220 ), rand ( 200, 220 ), rand ( 200, 220 ) );
    $start_x = rand ( 0,150 );
    $start_y = rand ( 0,70 );
    $end_x = $start_x + rand ( 0,5 );
    $end_y = $start_y + rand ( 0,5 );
    imageline ( $im, $start_x, $start_y, $end_x, $end_y, $lines );
}

$logo = imagecreatefrompng ( 'logo.png' );

$alt = rand ( -130, -30 );
$larg = rand ( -130, -30 );

imagecopy($im, $logo, $larg, $alt, 0, 0, 150 - $larg, 70 - $alt);

// Cria o array com as letras do captcha
$letters = array_merge ( range ( 'A', 'Z' ) , range ( 2, 9 ) );

// remove as letras que possam confundir o usuário
unset ( $letters[ array_search ( 'O', $letters ) ] );
unset ( $letters[ array_search ( 'Q', $letters ) ] );
unset ( $letters[ array_search ( 'I', $letters ) ] );
unset ( $letters[ array_search ( '5', $letters ) ] );
unset ( $letters[ array_search ( 'S', $letters ) ] );

// mistura as letras
shuffle($letters); 

// pega somente 4 posicoes (letras) do array 
$letters = array_slice($letters, 0, 4);

// transforma o array em uma string
$secure_text = implode('', $letters);

// escreve as letras na imagem
$i = 0;
foreach ( $letters as $letter):
    $text_color = imagecolorallocate ( $im, rand ( 0,100 ), rand ( 10,100 ), rand ( 0,100 ) );
	if ( ! function_exists ( 'imagefttext' ) )
    	imagestring ( $im, 10, 20 + ( $i * 25 ) + rand ( -5, +5 ), 10 + rand ( 10, 30 ),  $letter, $text_color );
	else
    	imagefttext ( $im, 35, rand ( -10, 10 ), 20 + ($i * 30) + rand ( -5, +5 ), 35 + rand ( 10, 30 ),  $text_color, './font.ttf', $letter );

    $i++;
endforeach;

// armazena na sessão o MD5 da imagem que foi gerada
$_SESSION ['ahctpac'] = md5 ( $secure_text );

imagepng($im);
imagedestroy($im);

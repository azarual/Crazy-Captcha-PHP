<?php
/**
 * @author Igor Escobar  (@igorescobar)
 * 
 * Crazy-Captcha-PHP - An image security generator 
 * to deny robot access.
 *
 * @source http://github.com/igorescobar/Crazy-Captcha-PHP
 * 
 * @original-source by @dgmike at http://github.com/dgmike/captcha
 */

// desabilitando o cache
header ( 'Content-type: image/png' );
header ( 'Pragma: no-cache');
header ( 'Cache-Control: private, no-cache, proxy-revalidate');

// é muito mais seguro usar sessoes.
session_start();

$captcha_size = array ( 300, 70 );
$captcha_total_letters = 5;


// Cria a imagem GD do captcha
$im = imagecreatetruecolor ( $captcha_size[0], $captcha_size[1] ) 
		or die("Cannot Initialize new GD image stream");

// Preenche o background de branco
imagefill ( $im, 0, 0, imagecolorallocate ( $im, 255, 255, 255 ) );

// Coloca os chanfros aleatorios na imagem
for ( $i = 0 ; $i < 300 ; $i++ ) {
    $lines = imagecolorallocatealpha ( $im, rand ( 0, 255 ), rand ( 0, 225 ), rand ( 0, 225 ) , 80 );
    $start_x = rand ( 0, $captcha_size[0] );
    $start_y = rand ( 0, $captcha_size[1] );
    $end_x = $start_x + rand ( 0, 1000);
    $end_y = $start_y + rand ( 0, 1000 );
    imageline ( $im, $start_x, $start_y, $end_x, $end_y, $lines );
}

// String com as letras que vao aparecer no captcha
$letters = 'ABCDEFGHJKLMNPRTUVWXYZ2346789';
$letters = str_split ( $letters );

shuffle ( $letters ); 

// pega somente 4 posicoes (letras) do array 
$letters = array_slice ( $letters, 0, $captcha_total_letters );

// transforma o array em uma string
$secure_text = implode ( '', $letters );

$font = imageloadfont ( './font.gdf' );

// escreve as letras na imagem
foreach ( $letters as $key => $letter):
	$text_color = imagecolorallocatealpha ( $im, rand ( 0, 100 ), rand ( 10, 100 ), rand ( 0, 100 ), 70 );
	$captcha_center = (($captcha_size[0] / 2) - ( $captcha_total_letters * 27 ) );
	imagestring ( $im, $font,  $captcha_center + ( $key * 50 ) , 5 + rand ( 5, 20 ), $letter, $text_color );
endforeach;

// armazena na sessão o MD5 da imagem que foi gerada
$_SESSION ['ahctpac'] = md5 ( $secure_text );

imagepng($im);
imagedestroy($im);

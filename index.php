<?php
/**
 * Created by PhpStorm.
 * User: Alfred
 * Date: 07.12.2017
 * Time: 22:11
 */

$ico = [
    'start' => '10.12.2018',
    'stop' => '11.12.2018',
    'name' => 'VISO',
    'thumbnail' => '8i20l584oyJ5NS_0qaBymcYSqg679IyF.png',
    'short_description' => 'VISO provides a full cycle of technologies for receiving and making payments.'
];
drawImage($ico);
function drawImage($ico)
{
    /* Create some objects */
    $image = new Imagick(realpath('./wiser_ico_banner_1200x630.png'));
    $icon = new Imagick(realpath('8i20l584oyJ5NS_0qaBymcYSqg679IyF.png'));
    $icon->resizeImage(180, 180, Imagick::FILTER_CATROM, 1);
    $draw = new ImagickDraw();

    $image->compositeImage($icon, Imagick::COMPOSITE_DEFAULT, 252, 326);

    /* Black text */
    $draw->setFillColor('white');

    /* Font properties */
//    $draw->setFont('fonts/Oxygen/Bold.ttf');
    $draw->setFont('fonts/Oxygen/Regular.ttf');
    $draw->setFontSize(28);

    /* Create text */
    $image->annotateImage($draw, 10, 45, 0,
        $ico['short_description']);

    list($lines, $lineHeight) = wordWrapAnnotation($image, $draw, $ico['short_description'], 560);
    for ($i = 0; $i < count($lines); $i++) {
        $image->annotateImage($draw, 452, 410 + $i * $lineHeight, 0, $lines[$i]);
    }

    /* Give image a format */
    $image->setImageFormat('png');

    /* Output the image with headers */
    header('Content-type: image/png');
    echo $image;
    $image->destroy();
    $icon->destroy();
}

/**
 * Make sure to set the font on the ImagickDraw Object first!
 *
 * @param $image Imagick
 * @param $draw ImagickDraw
 * @param $text string The text you want to wrap
 * @param $maxWidth int the maximum width in pixels for your wrapped "virtual" text box
 * @return array
 */
function wordWrapAnnotation($image, $draw, $text, $maxWidth)
{
    $text = trim($text);

    $words = preg_split('%\s%', $text, -1, PREG_SPLIT_NO_EMPTY);
    $lines = array();
    $i = 0;
    $lineHeight = 0;

    while (count($words) > 0) {
        $metrics = $image->queryFontMetrics($draw, implode(' ', array_slice($words, 0, ++$i)));
        $lineHeight = max($metrics['textHeight'], $lineHeight);

        // check if we have found the word that exceeds the line width
        if ($metrics['textWidth'] > $maxWidth or count($words) < $i) {
            // handle case where a single word is longer than the allowed line width (just add this as a word on its own line?)
            if ($i == 1) {
                $i++;
            }

            $lines[] = implode(' ', array_slice($words, 0, --$i));
            $words = array_slice($words, $i);
            $i = 0;
        }
    }

    return array($lines, $lineHeight);
}
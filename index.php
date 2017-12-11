<?php
/**
 * Created by PhpStorm.
 * User: Alfred
 * Date: 07.12.2017
 * Time: 22:11
 */

$ico = [
    1 => [
        'start_date' => '10.12.2018',
        'end_date' => '11.12.2018',
        'name' => 'VISO',
        'thumbnail' => '8i20l584oyJ5NS_0qaBymcYSqg679IyF.png',
        'short_description' => 'VISO provides a full cycle of technologies for receiving and making payments.',
        'rating_average' => '4.5'
    ],
    2 => [
        'start_date' => '17.11.2016',
        'end_date' => '17.11.2017',
        'name' => 'Cryptopay',
        'thumbnail' => 'yMD4s5bJ_Xo1BH30Yn_jNNp94NH6k3HJ.jpg',
        'short_description' => 'Genesis is the platform for the private trust management market, build on Blockchain
            technology and Smart Contract',
        'rating_average' => '3.6'
    ],
    3 => [
        'start_date' => '17.11.2016',
        'end_date' => '17.11.2017',
        'name' => 'Pulsar Venture Capital ICOOOOOOO',
        'thumbnail' => 'yMD4s5bJ_Xo1BH30Yn_jNNp94NH6k3HJ.jpg',
        'short_description' => 'f dsaf sdf adsf sadf asdfFund Platform is a platform for building trade and investment crypto funds at the decentralized, convenient and secure s',
        'rating_average' => '5.0'
    ],
];

drawBanner($ico[3]);
//drawRating($ico[3]);


function drawRating($ico)
{
//    $background = 'wiser_ico_rating_470x90.png';
    $background = 'wiser_ico_rating_470x90_origin.png';
    $countStars = round(str_replace(',', '.', $ico['rating_average']), 0, PHP_ROUND_HALF_UP);
    $nameMax = 27;
    $name = $ico['name'];
    $nameSize = mb_strlen($name);
    if ($nameSize > 27) {
        $name = substr($name, 0, $nameMax - 3) . '...';
        $nameSize = mb_strlen($name);
    }

    // get size of name, remove 12 chars, multiply by size of a letter
    $nameStart = $nameSize - 12;
    $displacement = $nameStart < 0 ? 0 : $nameStart * 7;
    $star = 'star.png';
    $ratingPosition = 0;

    /* Create some objects */
    $image = new Imagick(realpath($background));
    $icon = new Imagick(realpath($ico['thumbnail']));
    $icon->resizeImage(38, 38, Imagick::FILTER_CATROM, 1);
    $star = new Imagick(realpath($star));
    $draw = new ImagickDraw();

    $image->compositeImage($icon, Imagick::COMPOSITE_DEFAULT, 112 - $displacement, 46);
    for ($i = 0; $i < $countStars; $i++) {
        $ratingPosition = 250 + 36 * $i;
        $image->compositeImage($star, Imagick::COMPOSITE_DEFAULT, $ratingPosition, 50);
    }
    $draw->setFillColor('white');

    // Draw name
    $draw->setFont('fonts/Oxygen/Bold.ttf');
    $draw->setTextKerning(0.8);
    $draw->setFontSize(14);
    $image->annotateImage($draw, 157 - $displacement, 69, 0, $name);
    $image->annotateImage($draw, $ratingPosition + 40, 69, 0, $ico['rating_average']);

    /* Give image a format */
    $image->setImageFormat('png');

    /* Output the image with headers */
    header('Content-type: image/png');
    echo $image;
    $icon->destroy();
    $star->destroy();
    $draw->destroy();
    $image->destroy();
}

function limitString ($longString, $max = 27){
    $string =
    $nameSize = mb_strlen($string);
    if ($nameSize > $max) {
        $longString = substr($longString, 0, $max - 3) . '...';
        $nameSize = mb_strlen($name);
    }
}


/**
 * @param $ico array
 */
function drawBanner($ico)
{
//    $background = 'wiser_ico_banner_1200x630.png';
    $background = 'wiser_ico_banner_1200x630_original.png';

    /* Create some objects */
    $image = new Imagick(realpath($background));
    $icon = new Imagick(realpath($ico['thumbnail']));
    $icon->resizeImage(180, 180, Imagick::FILTER_CATROM, 1);
    $draw = new ImagickDraw();

    $image->compositeImage($icon, Imagick::COMPOSITE_DEFAULT, 252, 326);

    $draw->setFillColor('white');

    // Draw name
    $draw->setFont('fonts/Oxygen/Bold.ttf');
    $draw->setTextKerning(2);
    $draw->setFontSize(55);
    $image->annotateImage($draw, 452, 380, 0, $ico['name']);
    // Draw start, end time
    $draw->setFont('fonts/Oxygen/Bold.ttf');
    $draw->setTextKerning(1);
    $draw->setFontSize(26);
    $image->annotateImage($draw, 360, 602, 0, $ico['start_date']);
    $image->annotateImage($draw, 726, 602, 0, $ico['end_date']);
    // Draw long text
    $draw->setFont('fonts/Oxygen/Regular.ttf');
    $draw->setTextKerning(1.6);
    $draw->setFontSize(26);
    list($lines, $lineHeight) = wordWrapAnnotation($image, $draw, $ico['short_description'], 600);
    for ($i = 0; $i < count($lines); $i++) {
        $image->annotateImage($draw, 452, 430 + $i * $lineHeight * 0.55, 0, $lines[$i]);
    }

    /* Give image a format */
    $image->setImageFormat('png');

    /* Output the image with headers */
    header('Content-type: image/png');
    echo $image;
    $image->destroy();
    $icon->destroy();
    $draw->destroy();
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

/**
 * Error if word is more than maxWidth
 * Ex: wordWrapAnnotation2($image, $draw, 'fsdafd123132132113s', 50);
 * @param $image Imagick
 * @param $draw ImagickDraw
 * @param $text string The text you want to wrap
 * @param $maxWidth int the maximum width in pixels for your wrapped "virtual" text box
 * @return array
 */
function wordWrapAnnotation2($image, $draw, $text, $maxWidth)
{
    $words = preg_split('%\s%', $text, -1, PREG_SPLIT_NO_EMPTY);
    $lines = array();
    $i = 0;
    $lineHeight = 0;

    while (count($words) > 0) {
        $metrics = $image->queryFontMetrics($draw, implode(' ', array_slice($words, 0, ++$i)));
        $lineHeight = max($metrics['textHeight'], $lineHeight);

        if ($metrics['textWidth'] > $maxWidth or count($words) < $i) {
            $lines[] = implode(' ', array_slice($words, 0, --$i));
            $words = array_slice($words, $i);
            $i = 0;
        }
    }

    return array($lines, $lineHeight);
}

/* Implement word wrapping... Ughhh... why is this NOT done for me!!!
*   OK... I know the algorithm sucks at efficiency, but it's for short messages, okay?
*
*   Make sure to set the font on the ImagickDraw Object first!
*   @param image the Imagick Image Object
*   @param draw the ImagickDraw Object
*   @param text the text you want to wrap
*   @param maxWidth the maximum width in pixels for your wrapped "virtual" text box
*   @return an array of lines and line heights
*/
function wordWrapAnnotation1(&$image, &$draw, $text, $maxWidth)
{
    $words = explode(" ", $text);
    $lines = array();
    $i = 0;
    $lineHeight = 0;
    while ($i < count($words)) {
        $currentLine = $words[$i];
        if ($i + 1 >= count($words)) {
            $lines[] = $currentLine;
            break;
        }
        //Check to see if we can add another word to this line
        $metrics = $image->queryFontMetrics($draw, $currentLine . ' ' . $words[$i + 1]);
        while ($metrics['textWidth'] <= $maxWidth) {
            //If so, do it and keep doing it!
            $currentLine .= ' ' . $words[++$i];
            if ($i + 1 >= count($words)) {
                break;
            }
            $metrics = $image->queryFontMetrics($draw, $currentLine . ' ' . $words[$i + 1]);
        }
        //We can't add the next word to this line, so loop to the next line
        $lines[] = $currentLine;
        $i++;
        //Finally, update line height
        if ($metrics['textHeight'] > $lineHeight) {
            $lineHeight = $metrics['textHeight'];
        }
    }
    return array($lines, $lineHeight);
}

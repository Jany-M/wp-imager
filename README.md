# WP Imager

## About

Script for WordPress that provides resizing, output customization and image caching.
Can be used inside or outside the loop.
If used inside a loop, the script will automatically retrieve an image from the post, following a priority pattern: featured image if found, otherwise take one random image from the post.
If used outside the loop for any image you want, must use $exturl.

**WP Imager** makes image management easier when it comes to manipulating and customizing WordPress images.
Furthermore, WP doesnt provide good-enough image functionality and is quite limited yet.
Lastly, I personally prefer caching outside of the standard WP media folder, to avoid clutter and absurdly large backups.

It uses [TimThumb](http://code.google.com/p/timthumb/) script, so this wouldn't be possible without its developers' work.

> Supports **[Filters](http://www.binarymoon.co.uk/2010/08/timthumb-image-filters/)** and **Cropping**.

## Requirements

- PHP 5.2.x or higher
- GD image library
- TimThumb script (provided)
- Custom .htaccess (provided)

## Get started

Place the provided `cache_img` folder in your site's root folder.
If you don't have an `.htaccess` yet, place the one provided in your site's root folder.
If you already have an `.htaccess`, then adapt it, following the one provided.
Place the `wp-imager.php` file in your WP template.
Call `wp-imager.php` from your functions.php.

```php
<?php include 'wp-imager.php'; ?>
```

> If you don't complete every step the script won't work.
> Make sure that `cache_img/cache` is writable, in case images are not displaying.

## Parameters

```php
<?php
wp_imager($width='100', $height='100', $crop = 1, $class='', $filter=null, $link=false, $exturl=null )
?>
```

<table>
  <tr>
    <th>Parameter</th>
    <th>Type</th>
    <th>Description & Options</th>
    <th>Default</th>
  </tr>
  <tr>
    <td><code>width</code></td>
    <td>int</td>
    <td>Resize dimension of width (dont put 'px' after size)</td>
    <td>100</td>
  </tr>
  <tr>
    <td><code>height</code></td>
    <td>int</td>
    <td>Resize dimension of height (dont put 'px' after size)</td>
    <td>100</td>
  </tr>
  <tr>
    <td><code>crop</code></td>
    <td>int</td>
    <td>Type of cropping to perform
    <code>0 = Resize to Fit exactly specified dimensions (no cropping) 	
    1 =	Crop and resize to best fit the dimensions (default)
    2 =	Resize proportionally to fit entire image into specified dimensions, and add borders if required
    3 =	Resize proportionally adjusting size of scaled image so there are no borders gaps</code></td>
    <td>1</td>
  </tr>
  <tr>
    <td><code>class</code></td>
    <td>string</td>
    <td>class name/names to append to image</td>
    <td>NULL</td>
  </tr>
  <tr>
    <td>~~<code>filter</code>~~</td>
    <td>int</td>
    <td>Not ready yet ~~Type of image filter to apply
    <code>1 = Negate – Invert colours
	2 = Grayscale – turn the image into shades of grey
	3 = Brightness – Adjust brightness of image. Requires 1 argument to specify the amount of brightness to add. Values can be negative to make the image darker.
	4 = Contrast – Adjust contrast of image. Requires 1 argument to specify the amount of contrast to apply. Values greater than 0 will reduce the contrast and less than 0 will increase the contrast.
	5 = Colorize/ Tint – Apply a colour wash to the image. Requires the most parameters of all filters. The arguments are RGBA
	6 = Edge Detect – Detect the edges on an image
	7 = Emboss – Emboss the image, can look nice when combined with the colorize filter above.
	8 = Gaussian Blur – blur the image, unfortunately you can’t specify the amount, but you can apply the same filter multiple times
	9 = Selective Blur – a different type of blur. Not sure what the difference is, but this blur is less strong than the Gaussian blur.
	10 = Mean Removal – Uses mean removal to create a “sketchy” effect.
	11 = Smooth – Makes the image smoother.~~
    </code></td>
    <td>int</td>
  </tr>
  <tr>
    <td><code>link</code></td>
    <td>bool</td>
    <td>Wraps the image in HTML <a href="">img</a>, pointing to the image's post, with title attribute filled with post's title for better SEO. Wont' work with <code>$exturl</code></td>
    <td>false</td>
  </tr>
  <tr>
    <td><code>exturl</code></td>
    <td>string</td>
    <td>URL of some external/custom image (eg. http://www.mysite.com/image.jpg)	</td>
    <td>NULL</td>
  </tr>
</table>


## Defaults

- Function always returns to avoid yet another parameter, so simply echo it in your code.
- For now, cropping is always done in the middle, zooming in the center.
- Images are always wrapped already in their HTML tag <img src="" />, with alt attribute filled with post's title for better SEO.
- Processed IMG's quality is always 100, but this is set through the .htaccess
- Caching is done in a cache_img folder, in the root of your website, therefore this script requires your .htaccess to follow certain rules OR IT WONT WORK, that's why there is a .htaccess_sample file for you to use/adapt.


### Usage

## Resize + default Cropping

```php
<?php
echo wp_imager(600, 350)
?>
```

## Resize with no Cropping

```php
<?php
echo wp_imager(600, 350, 0)
?>
```

## Resize + default Cropping + Image Class

```php
<?php
echo wp_imager(600, 350, '', 'img-responsive')
?>
```

> Check the [guide to filters](Check demos here http://www.binarymoon.co.uk/demo/timthumb-filters/#combinations)

## Resize + default Cropping + WP post link

```php
<?php
echo wp_imager(600, 350, '', '', '', true)
?>
```

## Resize + default Cropping + custom img URL (outside the loop)

```php
<?php
echo wp_imager(600, 350, '', '', '', '', 'http://www.domain.com/image.jpg')
?>
```


## History

**WP Total Image Tool 1.0 - 28/1/2014**

- `release` version 1.0

## Credits

Copyright (c) 2014 - Jany Martelli @ [Shambix](http://www.shambix.com)
Released under the [GPL v3 License](http://choosealicense.com/licenses/gpl-v3/).

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/cosenary/instagram-php-api/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
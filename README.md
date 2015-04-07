# WP Imager

`v 1.8`

## About

Script for WordPress that provides resizing, output customization and image caching.

Can be used inside or outside the loop.

If used inside a loop, the script will automatically retrieve an image from the post, following a priority pattern: featured image if found, otherwise take one random image from the post.

If used outside the loop for any image you want, must use $exturl.

**WP Imager** makes image management easier when it comes to manipulating and customizing WordPress images.

Furthermore, WP doesnt provide good-enough image functionality and is quite limited yet.

Lastly, I personally prefer caching outside of the standard WP media folder, to avoid clutter and absurdly large backups.

> Uses **[TimThumb](http://code.google.com/p/timthumb/)**
> **[WPML](https://wpml.org/)** fully compatible
> Basic support **WordPress [Jetpack](http://jetpack.me/)'s [Photon](https://developer.wordpress.com/docs/photon/api/) module**

## To-Do

- Enhance support for [Jetpack](http://jetpack.me/)'s [Photon](https://developer.wordpress.com/docs/photon/api/)


## Requirements

- PHP 5.2.x or higher
- GD image library
- TimThumb script (provided)
- Custom .htaccess (provided)

## Get started

Place the provided `cache_img` folder in your site's root folder.
Place the `wp-imager.php` file in your WP template.
Call `wp-imager.php` from your functions.php.

> If you want to have pretty img urls, then:
> - If you don't have an `.htaccess` yet, place the one provided in your site's root folder.
> - If you already have an `.htaccess`, then adapt it, following the one provided.

```php
<?php include 'wp-imager.php'; ?>
```

> If you don't complete every step the script won't work.
> Make sure that `cache_img/cache` is writable, in case images are not displaying.

## Parameters

```php
<?php
wp_imager($width=null, $height=null, $crop=null, $class=null,$link=false, $exturl=null, $nohtml=false)
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
    0 = Resize to Fit exactly specified dimensions (no cropping) 	
    1 =	Crop and resize to best fit the dimensions (default)
    2 =	Resize proportionally to fit entire image into specified dimensions, and add borders if required
    3 =	Resize proportionally adjusting size of scaled image so there are no borders gaps</td>
    <td>1</td>
  </tr>
  <tr>
    <td><code>class</code></td>
    <td>string</td>
    <td>class name/names to append to image</td>
    <td>NULL</td>
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
  <tr>
    <td><code>nohtml</code></td>
    <td>bool</td>
    <td>When false,images are wrapped already in their HTML tag <img src="" />, with alt attribute filled with post's title for better SEO. If true, only the image url is returned</td>
    <td>false</td>
  </tr>
</table>


## Defaults

- Function always returns to avoid yet another parameter, so simply echo it in your code.
- For now, cropping is always done in the middle, zooming in the center.
- Processed IMG's quality is always 100, but this is set through the .htaccess
- Caching is done in a cache_img folder, in the root of your website
- Pretty img urls are disabled by default. To enable it change $htaccess to true and adapt the .htaccess provided with the script.

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

## Resize + default Cropping + WP post link

```php
<?php
echo wp_imager(600, 350, '', '', true)
?>
```

## Resize + default Cropping + custom img URL (outside the loop)

```php
<?php
echo wp_imager(600, 350, '', '', '', 'http://www.domain.com/image.jpg')
?>
```

## Resize + default Cropping + no html wrapper

```php
<?php
echo wp_imager(600, 350, '', '', '', '', true)
?>
```

### Conflicting Params

Clearly there are some parameters you cannot use together and as the script is specifically made to use within WordPress, restrictions may apply.

For example:
- <code>$link = true</code> and <code>$nohtml = true</code> dont make sense together, as you can imagine (<code>$nohtml = true</code> wins anyway)
- <code>$class</code> won't do anything if <code>$nohtml = true</code>
- <code>$link = true</code> and <code>$exturl</code> will output a broken or empty post url, if <code>$exturl</code> is not a post image (yes, <code>$exturl</code> should work also for images within WordPress but I haven't tested it yet)


## History

** 7/4/2015
- version 1.8
- Fixes to WPML compatibility bug
- Support for Photon

** 23/3/2015
- version 1.5
- Fixes to WPML compatibility

** 2/12/2014**
- added compatibility with WPML

** 3/2/2014**
- added $nohtml param
- added $htaccess var
- fixed various bugs

** 29/1/2014**
- `release` version 1.0

## Credits

TimThumbs (discontinued): [BinaryMoon](http://code.google.com/p/timthumb/)

## Author

Jany Martelli @ [Shambix](http://www.shambix.com)

## License

Released under the [GPL v3 License](http://choosealicense.com/licenses/gpl-v3/)